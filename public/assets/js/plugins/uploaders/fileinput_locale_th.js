(function ($) {
    "use strict";

    $.fn.fileinputLocales['th'] = {
        fileSingle: 'ไฟล์',
        filePlural: 'ไฟล์',
        browseLabel: 'เลือก &hellip;',
        removeLabel: 'ลบ',
        removeTitle: 'ลบไฟล์ที่เลือก',
        cancelLabel: 'ยกเลิก',
        cancelTitle: 'Abort ongoing upload',
        uploadLabel: 'อัพโหลด',
        uploadTitle: 'อัพโหลดไฟล์ที่เลือก',
        msgZoomTitle: 'ดูรายละเอียด',
        msgZoomModalHeading: 'Detailed Preview',
        msgSizeTooLarge: 'ไฟล์ "{name}" (<b>{size} KB</b>) มีขนาดใหญ่เกิน อัพโหลดได้สูงสุด <b>{maxSize} KB</b>.',
        msgFilesTooLess: 'You must select at least <b>{n}</b> {files} to upload.',
        msgFilesTooMany: 'Number of files selected for upload <b>({n})</b> exceeds maximum allowed limit of <b>{m}</b>.',
        msgFileNotFound: 'File "{name}" not found!',
        msgFileSecured: 'Security restrictions prevent reading the file "{name}".',
        msgFileNotReadable: 'File "{name}" is not readable.',
        msgFilePreviewAborted: 'File preview aborted for "{name}".',
        msgFilePreviewError: 'An error occurred while reading the file "{name}".',
        msgInvalidFileType: 'Invalid type for file "{name}". Only "{types}" files are supported.',
        msgInvalidFileExtension: 'Invalid extension for file "{name}". Only "{extensions}" files are supported.',
        msgUploadAborted: 'The file upload was aborted',
        msgValidationError: 'File Upload Error',
        msgLoading: 'Loading file {index} of {files} &hellip;',
        msgProgress: 'Loading file {index} of {files} - {name} - {percent}% completed.',
        msgSelected: 'เลือก {n} {files}',
        msgFoldersNotAllowed: 'Drag & drop files only! Skipped {n} dropped folder(s).',
        msgImageWidthSmall: 'Width of image file "{name}" must be at least {size} px.',
        msgImageHeightSmall: 'Height of image file "{name}" must be at least {size} px.',
        msgImageWidthLarge: 'Width of image file "{name}" cannot exceed {size} px.',
        msgImageHeightLarge: 'Height of image file "{name}" cannot exceed {size} px.',
        msgImageResizeError: 'Could not get the image dimensions to resize.',
        msgImageResizeException: 'Error while resizing the image.<pre>{errors}</pre>',
        dropZoneTitle: 'Drag & drop files here &hellip;',
        fileActionSettings: {
            removeTitle: 'Remove file',
            uploadTitle: 'Upload file',
            indicatorNewTitle: 'Not uploaded yet',
            indicatorSuccessTitle: 'Uploaded',
            indicatorErrorTitle: 'Upload Error',
            indicatorLoadingTitle: 'Uploading ...'
        }
    };
})(window.jQuery);