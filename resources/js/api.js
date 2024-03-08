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

    getDataField(resource, attribute, folder, filter,isMultipleSelection,storageDisk) {
        let storage_disk = window.storeageDisk;
        return axios
            .get(`/nova-r64-api/${resource}/${attribute}/data`, {
                params: {
                    folder,
                    filter,
                    isMultipleSelection,
                    storage_disk
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
        let storage_disk = window.storeageDisk;

        return axios
            .post('/nova-r64-api/actions/move', {
                type: moveType,
                old: oldPath,
                path: newPath,
                storage_disk
            })
            .then(response => response.data);
    },

    createFolder(folderName, currentFolder,isCreateSameName) {
        let storage_disk = window.storeageDisk;

        return axios
            .post('/nova-r64-api/actions/create-folder', {
                folder: folderName,
                current: currentFolder,
                isCreateSameName:isCreateSameName,
                storage_disk:storage_disk
            })
            .then(response => response.data);
    },

    removeDirectory(currentFolder) {
        let storage_disk = window.storeageDisk;
        return axios
            .post('/nova-r64-api/actions/delete-folder', {
                current: currentFolder,
                storage_disk:storage_disk

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
        let storage_disk = window.storeageDisk;
        return axios
            .post('/nova-r64-api/actions/get-info', { file: file,storage_disk })
            .then(response => response.data);
    },

    removeFile(file) {
        let storage_disk = window.storeageDisk;
        return axios
            .post('/nova-r64-api/actions/remove-file', { file: file,storage_disk })
            .then(response => response.data);
    },

    renameFile(file, name) {
        let storage_disk = window.storeageDisk;
        return axios
            .post('/nova-r64-api/actions/rename-file', {
                file: file,
                name: name,
                storage_disk
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
        let storage_disk = window.storeageDisk;
        return axios
            .post('/nova-r64-api/actions/rename-directory', {
                path: path,
                name: name,
                storage_disk
            })
            .then(response => response.data);
    },

    eventFolderUploaded(path) {
        
        return axios
            .post('/nova-r64-api/events/folder', { path: path })
            .then(response => response.data);
    },
};
