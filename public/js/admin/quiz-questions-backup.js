/**
 * Quiz Questions Management JavaScript
 * Modern, conflict-free implementation with Bootstrap styling
 */

let currentEditingQuestion = null;
let answerOptionCount = 0;
let quiz_id = null;

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Get quiz ID from meta tag
    const quizIdMeta = document.querySelector('meta[name="quiz-id"]');
    if (quizIdMeta) {
        quiz_id = quizIdMeta.getAttribute('content');
    }

    // Setup CSRF token for all AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (csrfToken) {
        console.log('CSRF token set globally');
    } else {
        console.warn('CSRF token not found!');
    }

    // Initialize SortableJS
    const sortableQuestionsList = document.getElementById('sortable-questions');
    if (sortableQuestionsList && typeof Sortable !== 'undefined') {
        new Sortable(sortableQuestionsList, {
            handle: '.handle',
            animation: 200,
            ghostClass: 'sortable-placeholder',
            onEnd: function (evt) {
                updateQuestionOrder();
            }
        });
    }

    // Initialize floating labels
    updateInputLabels();    // Add event listeners for input changes
    document.addEventListener('input', function(e) {
        if (e.target.matches('.qm-input-group input, .qm-input-group textarea, .qm-input-group select')) {
            updateInputLabels();
        }
    });

    document.addEventListener('change', function(e) {
        if (e.target.matches('.qm-input-group select')) {
            updateInputLabels();
        }
    });
});

function showQuestionBuilder() {
    const builderCard = document.getElementById('questionBuilderCard');
    if (builderCard) {
        builderCard.style.display = 'block';
        builderCard.classList.add('active');
        builderCard.scrollIntoView({ behavior: 'smooth' });

        // Reset form
        resetQuestionForm();

        // Hide error messages
        hideErrorMessages();

        // Add initial answer options
        answerOptionCount = 0;
        addAnswerOption();
        addAnswerOption();

        // Focus on the question text field
        setTimeout(() => {
            const questionText = document.getElementById('questionText');
            if (questionText) {
                questionText.focus();
            }
        }, 300);
    }
}

function hideErrorMessages() {
    const errorMessages = document.getElementById('error-messages');
    if (errorMessages) {
        errorMessages.style.display = 'none';
        const errorText = document.getElementById('error-text');
        if (errorText) {
            errorText.textContent = '';
        }
    }
}

function showErrorMessage(message) {
    const errorMessages = document.getElementById('error-messages');
    const errorText = document.getElementById('error-text');
    
    if (errorMessages && errorText) {
        errorText.textContent = message;
        errorMessages.style.display = 'flex';
        errorMessages.scrollIntoView({ behavior: 'smooth' });
    }
}

function cancelEditQuestion() {
    const builderCard = document.getElementById('questionBuilderCard');
    if (builderCard) {
        builderCard.style.display = 'none';
        builderCard.classList.remove('active');
    }
    
    resetQuestionForm();
    hideErrorMessages();
    currentEditingQuestion = null;
}

function resetQuestionForm() {
    const form = document.getElementById('questionForm');
    if (form) {
        form.reset();
    }

    const questionId = document.getElementById('questionId');
    if (questionId) {
        questionId.value = '';
    }

    const answerOptions = document.getElementById('answerOptions');
    if (answerOptions) {
        answerOptions.innerHTML = '';
    }

    currentEditingQuestion = null;
    answerOptionCount = 0;
}

function addAnswerOption() {
    const container = document.getElementById('answerOptions');
    if (!container) return;

    const optionIndex = answerOptionCount;
    const optionLabel = String.fromCharCode(65 + optionIndex); // A, B, C, D...

    const optionHtml = `
        <div class="answer-option-item" data-option="${optionLabel}">
            <div class="answer-option-header">
                <div class="answer-label">${optionLabel}</div>
                <div class="form-group" style="flex: 1; margin-bottom: 0;">
                    <input type="text" name="pilihan_jawaban[]" class="form-control-custom" placeholder="Masukkan pilihan jawaban..." required>
                </div>
                <div class="correct-answer-toggle">
                    <input type="radio" name="jawaban_benar" value="${optionLabel}" id="correct_${optionLabel}" style="margin-right: 0.5rem;">
                    <label for="correct_${optionLabel}" style="font-size: 0.875rem; margin: 0;">Jawaban Benar</label>
                </div>
                ${optionIndex > 1 ? `
                    <button type="button" class="btn-custom btn-danger btn-sm" onclick="removeAnswerOption('${optionLabel}')" style="margin-left: 0.75rem;">
                        <i class="fas fa-trash"></i>
                    </button>
                ` : ''}
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', optionHtml);
    answerOptionCount++;

    // Update answer labels if option becomes correct
    const correctRadio = document.getElementById(`correct_${optionLabel}`);
    if (correctRadio) {
        correctRadio.addEventListener('change', function() {
            updateAnswerOptionStyles();
        });
    }
}

function updateAnswerOptionStyles() {
    const answerOptions = document.querySelectorAll('.answer-option-item');
    answerOptions.forEach(option => {
        const radio = option.querySelector('input[type="radio"]');
        const label = option.querySelector('.answer-label');
        
        if (radio && radio.checked) {
            option.classList.add('correct');
            if (label) label.classList.add('correct');
        } else {
            option.classList.remove('correct');
            if (label) label.classList.remove('correct');
        }
    });
}

function removeAnswerOption(optionLabel) {
    const optionElement = document.querySelector(`[data-option="${optionLabel}"]`);
    if (optionElement) {
        optionElement.remove();
    }
}

function saveQuestion() {
    // Clear any previous error messages
    hideErrorMessages();

    const form = document.getElementById('questionForm');
    if (!form) return;

    // Get form data
    const questionId = document.getElementById('questionId')?.value;
    const questionText = document.getElementById('questionText')?.value?.trim();
    const questionWeight = document.getElementById('questionWeight')?.value;
    
    // Get answer options
    const answerInputs = document.querySelectorAll('input[name="pilihan_jawaban[]"]');
    const correctAnswer = document.querySelector('input[name="jawaban_benar"]:checked');
    
    // Validation
    if (!questionText) {
        showErrorMessage('Pertanyaan tidak boleh kosong');
        return;
    }
    
    if (!questionWeight || questionWeight < 1) {
        showErrorMessage('Bobot nilai harus diisi dengan nilai minimal 1');
        return;
    }
    
    if (answerInputs.length < 2) {
        showErrorMessage('Minimal harus ada 2 pilihan jawaban');
        return;
    }
    
    if (!correctAnswer) {
        showErrorMessage('Pilih salah satu jawaban yang benar');
        return;
    }
    
    // Check if all answer options are filled
    let allAnswersFilled = true;
    const answers = [];
    answerInputs.forEach((input, index) => {
        const value = input.value.trim();
        if (!value) {
            allAnswersFilled = false;
        } else {
            answers.push(value);
        }
    });
    
    if (!allAnswersFilled) {
        showErrorMessage('Semua pilihan jawaban harus diisi');
        return;
    }

    // Prepare data for submission
    const formData = new FormData();
    formData.append('pertanyaan', questionText);
    formData.append('bobot_nilai', questionWeight);
    formData.append('jawaban_benar', correctAnswer.value);
    
    answers.forEach((answer, index) => {
        formData.append('pilihan_jawaban[]', answer);
    });

    // Add CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (csrfToken) {
        formData.append('_token', csrfToken);
    }

    // Determine URL and method
    let url, method;
    if (questionId) {
        // Update existing question
        url = `/admin/quiz-questions/${questionId}`;
        method = 'PUT';
        formData.append('_method', 'PUT');
    } else {
        // Create new question
        url = `/admin/quiz/${quiz_id}/questions`;
        method = 'POST';
    }

    // Submit the form
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message || 'Pertanyaan berhasil disimpan',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                // Reload the page to show updated questions
                window.location.reload();
            });
        } else {
            showErrorMessage(data.message || 'Terjadi kesalahan saat menyimpan pertanyaan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('Terjadi kesalahan koneksi. Silakan coba lagi.');
    });
}

    const formData = new FormData(form);

    // Check CSRF token first
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'CSRF token not found. Please refresh the page and try again.',
            confirmButtonColor: '#667eea',
        });
        return;
    }

    // Validate form
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    // Check if at least one correct answer is selected
    const correctAnswer = formData.get('jawaban_benar');
    if (!correctAnswer) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Pilih jawaban yang benar!',
            confirmButtonColor: '#667eea',
        });
        return;
    }

    // Check for empty answer options
    const answerOptions = formData.getAll('pilihan_jawaban[]');
    const emptyOptions = answerOptions.filter(option => !option.trim());
    if (emptyOptions.length > 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Pilihan Jawaban Kosong',
            text: 'Semua pilihan jawaban harus diisi.',
            confirmButtonColor: '#667eea',
        });
        return;
    }

    const questionId = document.getElementById('questionId').value;
    const url = questionId ?
        `/admin/courses/quiz-questions/${questionId}` :
        `/admin/courses/quizzes/${quiz_id}/questions`;

    console.log('Saving question to URL:', url);
    console.log('Quiz ID:', quiz_id);
    console.log('Question ID:', questionId || 'New Question');

    const method = questionId ? 'PUT' : 'POST';

    // Convert FormData to JSON object properly
    const data = {
        pertanyaan: formData.get('pertanyaan'),
        bobot_nilai: formData.get('bobot_nilai'),
        jawaban_benar: formData.get('jawaban_benar'),
        pilihan_jawaban: answerOptions,
    };

    if (questionId) {
        data.question_id = questionId;
    }

    // Show loading
    Swal.fire({
        title: 'Menyimpan...',
        text: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    console.log('Sending data:', data);

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', [...response.headers.entries()]);

        if (!response.ok) {
            return response.json().then(errorData => {
                console.error('Server error response:', errorData);
                throw new Error(errorData.message || 'Terjadi kesalahan pada server');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                confirmButtonColor: '#667eea',
            }).then(() => {
                location.reload();
            });
        } else {
            throw new Error(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error saving question:', error);

        // Close any loading dialog
        Swal.close();

        if (error.errors) {
            // Handle validation errors
            showErrorMessages(error.errors);

            // Scroll to error messages
            const errorContainer = document.getElementById('error-messages');
            if (errorContainer) {
                errorContainer.scrollIntoView({ behavior: 'smooth' });
            }
        } else {
            // Get more details about the error
            let errorMessage = 'Terjadi kesalahan saat menyimpan pertanyaan.';

            if (error.message) {
                errorMessage += '<br>Detail: ' + error.message;
            }

            // Show detailed error
            const errorContainer = document.getElementById('error-messages');
            if (errorContainer) {
                errorContainer.innerHTML = errorMessage;
                errorContainer.style.display = 'block';
                errorContainer.scrollIntoView({ behavior: 'smooth' });
            }

            // Show general error
            Swal.fire({
                icon: 'error',
                title: 'Gagal Menyimpan Pertanyaan',
                html: errorMessage + '<br><br>Silahkan periksa form anda dan coba lagi.',
                confirmButtonColor: '#667eea',
            });
        }
    });
}

function editQuestion(questionId) {
    // Show loading
    Swal.fire({
        title: 'Memuat...',
        text: 'Mengambil data pertanyaan',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Fetch question data
    fetch(`/admin/courses/quiz-questions/${questionId}/edit`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        console.log('Edit response status:', response.status);
        if (!response.ok) {
            throw new Error('Tidak dapat mengambil data pertanyaan');
        }
        return response.json();
    })
    .then(data => {
        if (data.success && data.question) {
            // Close the loading dialog
            Swal.close();

            // Set question ID for edit mode
            const questionIdField = document.getElementById('questionId');
            if (questionIdField) {
                questionIdField.value = questionId;
            }

            // Set question text
            const questionText = document.getElementById('questionText');
            if (questionText) {
                questionText.value = data.question.pertanyaan;
            }

            // Set question weight
            const questionWeight = document.getElementById('questionWeight');
            if (questionWeight) {
                questionWeight.value = data.question.bobot_nilai;
            }

            // Clear existing answer options
            const answerOptions = document.getElementById('answerOptions');
            if (answerOptions) {
                answerOptions.innerHTML = '';
            }

            // Reset counter
            answerOptionCount = 0;

            // Add answer options from the question data
            try {
                const options = data.question.pilihan_jawaban;
                const correctAnswer = data.question.jawaban_benar;

                console.log('Options data:', options);

                if (Array.isArray(options) && options.length > 0) {
                    options.forEach((option, index) => {
                        const optionLabel = String.fromCharCode(65 + index);
                        addAnswerOptionWithValue(option, optionLabel === correctAnswer);
                    });
                } else if (typeof options === 'object' && options !== null) {
                    // Handle case where options might be an object with keys A, B, C, etc.
                    Object.keys(options).forEach((key, index) => {
                        const optionLabel = String.fromCharCode(65 + index);
                        addAnswerOptionWithValue(options[key], optionLabel === correctAnswer);
                    });
                } else {
                    throw new Error('Invalid options format');
                }
            } catch (e) {
                console.error('Error processing answer options:', e);
                // Add default empty options if error
                addAnswerOption();
                addAnswerOption();
            }

            // Show the form
            const builderCard = document.getElementById('questionBuilderCard');
            if (builderCard) {
                builderCard.style.display = 'block';
                builderCard.scrollIntoView({ behavior: 'smooth' });
            }

            // Set current editing question
            currentEditingQuestion = questionId;
        } else {
            throw new Error(data.message || 'Tidak dapat memuat data pertanyaan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Terjadi kesalahan saat memuat data pertanyaan',
            confirmButtonColor: '#667eea',
        });
    });
}

function addAnswerOptionWithValue(optionValue, isCorrect) {
    const container = document.getElementById('answerOptions');
    if (!container) return;

    const optionIndex = answerOptionCount;
    const optionLabel = String.fromCharCode(65 + optionIndex); // A, B, C, D...

    const optionHtml = `
        <div class="answer-option-builder mb-3" data-option="${optionLabel}">
            <div class="qm-input-group">
                <input type="text" name="pilihan_jawaban[]" placeholder=" " value="${optionValue}" required>
                <label>Pilihan ${optionLabel}</label>
            </div>
            <div class="form-check mt-2">
                <input class="form-check-input" type="radio" name="jawaban_benar" value="${optionLabel}" id="correct_${optionLabel}" ${isCorrect ? 'checked' : ''}>
                <label class="form-check-label" for="correct_${optionLabel}">
                    Jawaban yang benar
                </label>
            </div>
            ${optionIndex > 1 ? `
                <button type="button" class="qm-btn qm-btn-danger qm-btn-sm mt-2" onclick="removeAnswerOption('${optionLabel}')">
                    <i class="fas fa-trash"></i>
                    <span>Hapus</span>
                </button>
            ` : ''}
        </div>
    `;

    container.insertAdjacentHTML('beforeend', optionHtml);
    answerOptionCount++;
}

function deleteQuestion(questionId) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Pertanyaan ini akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/courses/quiz/questions/${questionId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Terhapus!', data.message, 'success')
                        .then(() => location.reload());
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                Swal.fire('Error!', error.message, 'error');
            });
        }
    });
}

function cancelEditQuestion() {
    const builderCard = document.getElementById('questionBuilderCard');
    if (builderCard) {
        builderCard.style.display = 'none';
    }
    resetQuestionForm();
}

function updateQuestionOrder() {
    const questions = document.querySelectorAll('#sortable-questions .qm-question-card');
    const orderData = [];

    questions.forEach((question, index) => {
        const questionId = question.getAttribute('data-question-id');
        orderData.push({
            question_id: questionId,
            urutan_pertanyaan: index + 1
        });

        // Update visual order number
        const numberElement = question.querySelector('.question-number');
        if (numberElement) {
            numberElement.textContent = index + 1;
        }
    });

    // Send order update to server
    fetch(`/admin/courses/quiz-questions/update-order`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ questions: orderData })
    })
    .catch(error => {
        console.error('Error updating order:', error);
    });
}

function previewQuiz() {
    // Get all questions for preview
    const questions = [];
    document.querySelectorAll('.qm-question-card').forEach((card, index) => {
        // Get question text
        const questionTextElement = card.querySelector('.question-text');
        if (!questionTextElement) return;

        const questionText = questionTextElement.textContent.trim();
        const questionNumber = index + 1;

        // Get options
        const options = [];
        card.querySelectorAll('.qm-answer-option').forEach((option) => {
            const optionTextElement = option.querySelector('.flex-1');
            if (optionTextElement) {
                const optionText = optionTextElement.textContent.trim().replace(/\s*\n\s*/g, ' ');
                const isCorrect = option.classList.contains('correct');
                options.push({ text: optionText, isCorrect });
            }
        });

        questions.push({
            number: questionNumber,
            text: questionText,
            options
        });
    });

    // If no questions, show error
    if (questions.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Tidak ada pertanyaan',
            text: 'Tambahkan pertanyaan terlebih dahulu untuk melihat preview quiz.',
            confirmButtonColor: '#667eea',
        });
        return;
    }

    // Get quiz title from meta or fallback
    const quizTitleMeta = document.querySelector('meta[name="quiz-title"]');
    const quizTitle = quizTitleMeta ? quizTitleMeta.getAttribute('content') : 'Quiz Preview';

    // Build preview HTML
    let previewHtml = `
        <div class="quiz-preview-container">
            <h5 class="mb-4">${quizTitle}</h5>
    `;

    questions.forEach(q => {
        previewHtml += `
            <div class="question-preview mb-4">
                <h6 class="mb-3">${q.number}. ${q.text}</h6>
                <div class="options-list">
        `;

        q.options.forEach((opt, idx) => {
            const optionLabel = String.fromCharCode(65 + idx);
            previewHtml += `
                <div class="option-preview ${opt.isCorrect ? 'option-correct' : ''}">
                    <span class="option-label">${optionLabel}</span>
                    <span class="option-text">${opt.text}</span>
                    ${opt.isCorrect ? '<i class="fas fa-check-circle text-success float-end"></i>' : ''}
                </div>
            `;
        });

        previewHtml += `
                </div>
            </div>
        `;
    });

    previewHtml += `</div>`;

    // Show preview in modal
    Swal.fire({
        title: 'Preview Quiz',
        html: previewHtml,
        width: '800px',
        customClass: {
            container: 'quiz-preview-modal',
            popup: 'quiz-preview-popup',
        },
        showCloseButton: true,
        confirmButtonColor: '#667eea',
        confirmButtonText: 'Tutup Preview'
    });

    // Add styles for preview
    const style = document.createElement('style');
    style.textContent = `
        .quiz-preview-popup {
            max-width: 800px;
            width: 100%;
        }
        .quiz-preview-container {
            text-align: left;
            padding: 20px 0;
        }
        .question-preview {
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 20px;
        }
        .question-preview:last-child {
            border-bottom: none;
        }
        .options-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .option-preview {
            display: flex;
            align-items: center;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            position: relative;
        }
        .option-correct {
            background: #d1e7dd;
            border: 1px solid #198754;
        }
        .option-label {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            background: #e9ecef;
            border-radius: 50%;
            margin-right: 12px;
            font-weight: bold;
        }
        .option-correct .option-label {
            background: #198754;
            color: white;
        }
    `;
    document.head.appendChild(style);
}

function showErrorMessages(errors) {
    const errorContainer = document.getElementById('error-messages');
    if (!errorContainer) return;

    errorContainer.innerHTML = '';
    errorContainer.style.display = 'block';

    // Add error header
    const errorHeader = document.createElement('div');
    errorHeader.innerHTML = '<strong><i class="fas fa-exclamation-triangle me-2"></i>Ada kesalahan dalam form:</strong>';
    errorContainer.appendChild(errorHeader);

    if (typeof errors === 'string') {
        errorContainer.innerHTML += `<p class="mt-2"><i class="fas fa-exclamation-circle me-2"></i>${errors}</p>`;
        return;
    }

    const errorList = document.createElement('ul');
    errorList.classList.add('mb-0', 'mt-2');
    errorList.style.paddingLeft = '20px';

    if (typeof errors === 'object') {
        // If errors is an object with error arrays
        Object.keys(errors).forEach(field => {
            errors[field].forEach(message => {
                const item = document.createElement('li');
                item.textContent = message;
                errorList.appendChild(item);
            });
        });
    }

    errorContainer.appendChild(errorList);
}

function hideErrorMessages() {
    const errorContainer = document.getElementById('error-messages');
    if (errorContainer) {
        errorContainer.innerHTML = '';
        errorContainer.style.display = 'none';
    }
}

function updateInputLabels() {
    // Update floating labels for all inputs
    document.querySelectorAll('.qm-input-group input, .qm-input-group textarea, .qm-input-group select').forEach(input => {
        const label = input.nextElementSibling;
        if (label && label.tagName === 'LABEL') {
            if (input.value.trim() !== '' || input.type === 'select-one' && input.value !== '') {
                label.classList.add('has-value');
            } else {
                label.classList.remove('has-value');
            }
        }
    });
}
