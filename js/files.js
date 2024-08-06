const dropArea = document.querySelector('.drop-area');
const dragText = dropArea.querySelector('h4');
const input = dropArea.querySelector('#input-file');
let files;


input.addEventListener('change', (e) => {
    files = input.files;
    try{
        dropArea.classList.add('active');
        showFiles(files);
        dropArea.classList.remove('active');
    }catch (error){
        console.log(error);
    }
});

dropArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropArea.classList.add('active');
    dragText.textContent = 'Suelta para subir el archivo';
})

dropArea.addEventListener('dragleave', (e) => {
    e.preventDefault();
    dropArea.classList.remove('active');
    dragText.textContent = 'Arrastra y suelta el archivo aquí';
})

dropArea.addEventListener('drop', (e) => {
    e.preventDefault();
    files = e.dataTransfer.files;
    showFiles(files);
    dropArea.classList.remove('active');
    dragText.textContent = 'Arrastra y suelta el archivo aquí';
})

function showFiles(files) {
    if (files.length == undefined){
        processFile(files);
    }else{
        for (const file of files) {
            processFile(file);
        }
    }
}

function processFile(file) {
    const docType = file.type;
    const validTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'image/jpeg', 'image/png', 'image/gif', 'image/jpg', 'application/zip', 'application/rar', 'video/mp4', 'audio/mpeg', 'audio/mp3', 'audio/wav'];
    if (validTypes.includes(docType)){
        const reader = new FileReader();
        const id = 'file-' + Math.random().toString(32).substring(7);
        
        reader.addEventListener('load', (e) => {
            const fileUrl = reader.result;
            const image = `
                <div id='${id}' class='file-container mb-2'>
                    <div class='status'>
                        <span>${file.name}</span>
                        <span class='status-text'>
                            Cargando...
                        </span>
                    </div>
                </div>
            `;

            document.querySelector('#preview').innerHTML += image;
        });
        reader.readAsDataURL(file);
        uploadFile(file, id);
    }else{
        alert('Tipo de archivo no permitido');
    }
}

async function uploadFile(file, id) {
    const formData = new FormData();
    formData.append('file', file);
    
    try{
        const response = await fetch('#.php', {  //!!! Aquí va la ruta del archivo php
            method: 'POST',
            body: formData
        });
        const responseText = await response.text();
        document.querySelector(`#${id} .status-text`).innerHTML = `<span class='success'>Archivo subido correctamente</span> <span class='bi bi-x-lg align-content-end' onclick='deleteFile("${id}")'></span>`;
        console.log(responseText);
    } catch (error) {
        document.querySelector(`#${id} .status-text`).innerHTML = `<span class='failure'>El archivo no pudo subirse</span>`;
    }
}

function deleteFile(id) {
    document.querySelector(`#${id}`).remove();
}

//!!! Para el php sugiere el siguiente código
// <?php

//* Check if file is uploaded
// if (isset($_FILES['file'])) {
//   $file = $_FILES['file'];
  
//* Validate file (optional)
//* You can add checks for file size, type, etc. here  
//   $allowed_extensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'gif', 'zip', 'rar'];
//   $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
//   if (!in_array($file_extension, $allowed_extensions)) {
//       echo "Error: Invalid file type.";
//       exit;
//   }
  
//* Generate a unique filename
//   $target_dir = "uploads/"; // Change this to your desired upload directory
//   $target_file = $target_dir . basename($file["name"]) . uniqid() . "." . $file_extension;

//* Move the uploaded file to the target directory
//   if (move_uploaded_file($file["tmp_name"], $target_file)) {
//     echo "The file " . basename( $file["name"]) . " has been uploaded.";
//* You can also return success code or data here
//   } else {
//     echo "Error: There was a problem uploading your file.";
//   }
// } else {
//   echo "Error: No file uploaded.";
// }

// ?>
