export const DragAndDrop = {
    bind: function(el, binding, vnode) {
        var element = vnode.componentInstance;
        var instance = vnode.context;
        var folder = false;

        if (binding.arg == 'folder' && element.file.id) {
            folder = instance.getFileById('folder', element.file.id);
        }

        instance.handleDragStart = function() {
            instance.currentDraggedFile = element;

            instance.$emit('drag-start');
        }.bind(instance);

        instance.handleDragOver = function(e) {
            if (e.preventDefault) {
                e.preventDefault();
            }
            if (instance.currentDraggedFile !== null) {
                folder.dragOver = true;
            }

            return false;
        }.bind(instance);

        instance.handleDragLeave = function() {
            folder.dragOver = false;
            // instance.currentDraggedFile = null;
        }.bind(instance);

        instance.handleDragEnd = function() {
            instance.currentDraggedFile = null;
        }.bind(instance);

        instance.handleDrag = function() {
            instance.$emit('drag');
        }.bind(instance);

        instance.handleDrop = function(e) {
            e.preventDefault();
            if (e.stopPropagation) {
                e.stopPropagation();
            }

            if (instance.currentDraggedFile !== null) {
                folder.dragOver = false;
                let oldPath = instance.currentDraggedFile.file.path;
                let newPath = element.file.path + '/' + instance.currentDraggedFile.file.name;
                instance.currentDraggedFile = null;

                instance.moveFile(oldPath, newPath);
            }

            return false;
        }.bind(instance);

        if (binding.arg == 'file') {
            el.setAttribute('draggable', 'true');
            el.addEventListener('dragstart', instance.handleDragStart, false);
            el.addEventListener('dragend', instance.handleDragEnd, false);
        }

        if (binding.arg == 'folder' && folder != false) {
            el.addEventListener('dragover', instance.handleDragOver, false);
            el.addEventListener('dragleave', instance.handleDragLeave, false);
            el.addEventListener('drop', instance.handleDrop, false);
        }
    },
    unbind: function(el, binding, vnode) {
        var instance = vnode.context;
        instance.currentDraggedFile = null;

        if (binding.arg == 'file') {
            el.removeAttribute('draggable');
            el.removeEventListener('dragstart', instance.handleDragStart);
        }

        if (binding.arg == 'folder') {
            el.removeEventListener('dragover', instance.handleDragOver);
            el.removeEventListener('dragleave', instance.handleDragLeave);
            el.removeEventListener('drag', instance.handleDrag);
        }
    },
};
