function previewFiles() {
    const preview = document.getElementById('file-preview');
    const files = document.querySelector('input[type=file]').files;

    for (const file of files) {
        const fileItem = document.createElement('div');
        fileItem.classList.add('file-item');

        const fileIcon = document.createElement('i');
        fileIcon.classList.add('file-icon');

        const fileName = document.createElement('div');
        fileName.classList.add('file-name');

        const fileRemove = document.createElement('i');
        fileRemove.classList.add('fas', 'fa-times', 'file-remove');
        fileRemove.setAttribute('aria-label', 'Eliminar archivo');
        fileRemove.addEventListener('click', () => {
            fileItem.remove();
        });

        if (file.type.startsWith('image/')) {
            fileIcon.classList.add('fas', 'fa-file-image');
        } else if (file.type === 'application/pdf') {
            fileIcon.classList.add('fas', 'fa-file-pdf');
        } else if (file.type === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
            fileIcon.classList.add('fas', 'fa-file-word');
        } else if (file.type.startsWith('video/')) {
            fileIcon.classList.add('fas', 'fa-file-video');
        } else {
            fileIcon.classList.add('fas', 'fa-file');
        }

        fileName.textContent = file.name;

        fileItem.appendChild(fileIcon);
        fileItem.appendChild(fileName);
        fileItem.appendChild(fileRemove);
        preview.appendChild(fileItem);
    }
}