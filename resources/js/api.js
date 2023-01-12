export default {
    getData(folder) {
        return window.axios
            .get('/nova-r64-api/data', {
                params: {
                    folder,
                },
            })
            .then(response => response.data);
    },

    getDataField(resource, attribute, folder, filter,isMultipleSelection) {
        return window.axios
            .get(`/nova-r64-api/${resource}/${attribute}/data`, {
                params: {
                    folder,
                    filter,
                    isMultipleSelection
                },
            })
            .then(response => response.data);
    },

    uploadFile() {
        return window.axios
            .post('/nova-r64-api/uploads/add')
            .then(response => response.data);
    },

    moveFile(oldPath, newPath) {
        return window.axios
            .post('/nova-r64-api/actions/move', {
                old: oldPath,
                path: newPath,
            })
            .then(response => response.data);
    },

    createFolder(folderName, currentFolder) {
        return window.axios
            .post('/nova-r64-api/actions/create-folder', {
                folder: folderName,
                current: currentFolder,
            })
            .then(response => response.data);
    },

    removeDirectory(currentFolder) {
        return window.axios
            .post('/nova-r64-api/actions/delete-folder', {
                current: currentFolder,
            })
            .then(response => response.data);
    },

    validatePassword(password){
        return window.axios
            .post('/nova-r64-api/actions/validatePassword', {
                password: password,
            })
            .then(response => response.data);
    },

    getInfo(file) {
        return window.axios
            .post('/nova-r64-api/actions/get-info', { file: file })
            .then(response => response.data);
    },

    removeFile(file) {
        return window.axios
            .post('/nova-r64-api/actions/remove-file', { file: file })
            .then(response => response.data);
    },

    renameFile(file, name) {
        return window.axios
            .post('/nova-r64-api/actions/rename-file', {
                file: file,
                name: name,
            })
            .then(response => response.data);
    },

    rename(path, name) {
        return window.axios
            .post('/nova-r64-api/actions/rename', {
                path: path,
                name: name,
            })
            .then(response => response.data);
    },

    eventFolderUploaded(path) {
        return window.axios
            .post('/nova-r64-api/events/folder', { path: path })
            .then(response => response.data);
    },

    renameDirectory(path, name) {
        return axios
            .post('/nova-r64-api/actions/rename-directory', {
                path: path,
                name: name,
            })
            .then(response => response.data);
    },
};