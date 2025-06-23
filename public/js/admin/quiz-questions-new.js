/**
 * Quiz Questions Management JavaScript
 * Professional, clean implementation for Bootstrap styling
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
});

function showQuestionBuilder() {
    const builderCard = document.getElementById('questionBuilderCard');
    if (builderCard) {
        builderCard.style.display = 'block';
        builderCard.classList.add('active');
        builderCard.scrollIntoView({ behavior: 'smooth' });

        // Reset form
        resetQuestionForm();
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

    // Show loading
    Swal.fire({
        title: 'Menyimpan...',
        text: 'Sedang menyimpan pertanyaan',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

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
        Swal.close();

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
        Swal.close();
        console.error('Error:', error);
        showErrorMessage('Terjadi kesalahan koneksi. Silakan coba lagi.');
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
    fetch(`/admin/quiz-questions/${questionId}/edit`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();

        if (data.success) {
            populateQuestionForm(data.question);
            showQuestionBuilder();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Gagal mengambil data pertanyaan'
            });
        }
    })
    .catch(error => {
        Swal.close();
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan saat mengambil data pertanyaan'
        });
    });
}

function populateQuestionForm(question) {
    // Set question ID for editing mode
    const questionIdField = document.getElementById('questionId');
    if (questionIdField) {
        questionIdField.value = question.question_id;
    }

    // Set question text
    const questionTextField = document.getElementById('questionText');
    if (questionTextField) {
        questionTextField.value = question.pertanyaan;
    }

    // Set question weight
    const questionWeightField = document.getElementById('questionWeight');
    if (questionWeightField) {
        questionWeightField.value = question.bobot_nilai;
    }

    // Clear existing answer options
    const answerContainer = document.getElementById('answerOptions');
    if (answerContainer) {
        answerContainer.innerHTML = '';
    }

    answerOptionCount = 0;
    currentEditingQuestion = question.question_id;

    // Add answer options
    const options = Array.isArray(question.pilihan_jawaban)
        ? question.pilihan_jawaban
        : JSON.parse(question.pilihan_jawaban || '[]');

    if (options.length > 0) {
        options.forEach((option, index) => {
            addAnswerOptionWithValue(option, String.fromCharCode(65 + index) === question.jawaban_benar);
        });
    } else {
        // Add default empty options
        addAnswerOption();
        addAnswerOption();
    }
}

function addAnswerOptionWithValue(optionValue, isCorrect) {
    const container = document.getElementById('answerOptions');
    if (!container) return;

    const optionIndex = answerOptionCount;
    const optionLabel = String.fromCharCode(65 + optionIndex);

    const optionHtml = `
        <div class="answer-option-item ${isCorrect ? 'correct' : ''}" data-option="${optionLabel}">
            <div class="answer-option-header">
                <div class="answer-label ${isCorrect ? 'correct' : ''}">${optionLabel}</div>
                <div class="form-group" style="flex: 1; margin-bottom: 0;">
                    <input type="text" name="pilihan_jawaban[]" class="form-control-custom" value="${optionValue}" placeholder="Masukkan pilihan jawaban..." required>
                </div>
                <div class="correct-answer-toggle">
                    <input type="radio" name="jawaban_benar" value="${optionLabel}" id="correct_${optionLabel}" ${isCorrect ? 'checked' : ''} style="margin-right: 0.5rem;">
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

    // Add event listener for radio change
    const correctRadio = document.getElementById(`correct_${optionLabel}`);
    if (correctRadio) {
        correctRadio.addEventListener('change', function() {
            updateAnswerOptionStyles();
        });
    }
}

function deleteQuestion(questionId) {
    Swal.fire({
        title: 'Hapus Pertanyaan?',
        text: 'Pertanyaan yang dihapus tidak dapat dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Menghapus...',
                text: 'Sedang menghapus pertanyaan',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const formData = new FormData();
            formData.append('_token', csrfToken);
            formData.append('_method', 'DELETE');

            // Delete question
            fetch(`/admin/quiz/questions/${questionId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus!',
                        text: data.message || 'Pertanyaan berhasil dihapus',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Gagal menghapus pertanyaan'
                    });
                }
            })
            .catch(error => {
                Swal.close();
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat menghapus pertanyaan'
                });
            });
        }
    });
}

function updateQuestionOrder() {
    const questionCards = document.querySelectorAll('.question-card');
    const orderData = [];

    questionCards.forEach((card, index) => {
        const questionId = card.getAttribute('data-question-id');
        if (questionId) {
            orderData.push({
                question_id: questionId,
                urutan_pertanyaan: index + 1
            });
        }
    });

    if (orderData.length === 0) return;

    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const formData = new FormData();
    formData.append('_token', csrfToken);
    formData.append('order_data', JSON.stringify(orderData));

    // Update order
    fetch('/admin/quiz-questions/update-order', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            console.error('Failed to update question order:', data.message);
        }
    })
    .catch(error => {
        console.error('Error updating question order:', error);
    });
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

function previewQuiz() {
    // This function can be implemented to show quiz preview
    Swal.fire({
        icon: 'info',
        title: 'Preview Quiz',
        text: 'Fitur preview akan segera tersedia',
        confirmButtonText: 'OK'
    });
}
