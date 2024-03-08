import axios from 'axios'

export default {
    getData(folder) {
        return axios
            .get('/nova-r64-api/data', {
                params: {
                    folder,
                },
            })
            .then(response => response.data);
    },

    getDataField(resource, attribute, folder, filter,isMultipleSelection) {
        return axios
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
        return axios
            .post('/nova-r64-api/uploads/add')
            .then(response => response.data);
    },

    moveFile(moveType,oldPath, newPath) {
        return axios
            .post('/nova-r64-api/actions/move', {
                type: moveType,
                old: oldPath,
                path: newPath,
            })
            .then(response => response.data);
    },

    createFolder(folderName, currentFolder,isCreateSameName) {
        return axios
            .post('/nova-r64-api/actions/create-folder', {
                folder: folderName,
                current: currentFolder,
                isCreateSameName:isCreateSameName
            })
            .then(response => response.data);
    },

    removeDirectory(currentFolder) {
        return axios
            .post('/nova-r64-api/actions/delete-folder', {
                current: currentFolder,
            })
            .then(response => response.data);
    },

    validatePassword(password){
        return axios
            .post('/nova-r64-api/actions/validatePassword', {
                password: password,
            })
            .then(response => response.data);
    },

    getInfo(file) {
        return axios
            .post('/nova-r64-api/actions/get-info', { file: file })
            .then(response => response.data);
    },

    removeFile(file) {
        return axios
            .post('/nova-r64-api/actions/remove-file', { file: file })
            .then(response => response.data);
    },

    renameFile(file, name) {
        return axios
            .post('/nova-r64-api/actions/rename-file', {
                file: file,
                name: name,
            })
            .then(response => response.data);
    },

    rename(path, name) {
        return renameFile
            .post('/nova-r64-api/actions/rename', {
                path: path,
                name: name,
            })
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

    eventFolderUploaded(path) {
        return axios
            .post('/nova-r64-api/events/folder', { path: path })
            .then(response => response.data);
    },
};
