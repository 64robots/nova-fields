<template id="liquor-json-viewer">
    <div class="json-viewer">
        <tree :data="treeData" :options="treeOptions">
            <span :class="[node.data.type]" class="viewer-item" slot-scope="{ node }">
                <span class="viewer-item__key" v-if="node.hasChildren()">
                    {{ node.text }}
                    <span v-if="node.collapsed()">
                        <template v-if="node.data.type == 'array'">
                            [ {{ node.children.length }} ]
                        </template>
                        <template v-else="">
                            { {{ node.children.length }} }
                        </template>
                    </span>
                </span>
                <span class="viewer-item__prop" v-else="">
                    <span class="viewer-item__value">
                        {{ node.data.objectKey }}
                    </span>
                </span>
            </span>
        </tree>
    </div>
</template>

<script>
import LiquorTree from 'liquor-tree';

export default {
    props: {
        json: {
            default: {},
            required: true,
        },
        name: {
            type: String,
            default: 'Zip content',
            required: true,
        },
    },

    components: {
        [LiquorTree.name]: LiquorTree,
    },

    data() {
        return {
            treeData: this.parser(this.json),
            treeOptions: {},
        };
    },

    methods: {
        isString(value) {
            return 'string' == typeof value;
        },

        isNaN(value) {
            return value !== value;
        },

        isBoolean(value) {
            return 'boolean' == typeof value;
        },

        isNumber(value) {
            return 'number' == typeof value;
        },

        isArray(value) {
            return Array.isArray(value);
        },

        isValue(value) {
            return !this.isArray(value) && !this.isPlainObject(value);
        },

        isPlainObject(value) {
            function isObject(obj) {
                return obj != null && typeof obj === 'object' && Array.isArray(obj) === false;
            }

            if (!isObject(value) || !value.constructor || !value.constructor.prototype) {
                return false;
            }

            return value.constructor.prototype.hasOwnProperty('isPrototypeOf') === true;
        },

        isIterable(value) {
            return this.isArray(value) || this.isPlainObject(value);
        },

        map(obj, fn) {
            return Object.keys(obj).map(key => {
                return fn(obj[key], key);
            });
        },

        transformObject(prop, key) {
            let obj = {
                text: key,
            };

            if (this.isIterable(prop)) {
                obj.children = this.map(prop, this.transformObject);
                obj.data = {
                    type: this.isArray(prop)
                        ? 'array'
                        : this.isPlainObject(prop)
                            ? 'object'
                            : 'unknown',
                };
            } else {
                obj.data = {
                    objectKey: prop || `${prop}`,
                    type: this.getType(prop),
                };
            }

            return obj;
        },

        getType(value) {
            if (this.isNaN(value)) {
                return 'viewer-type--nan';
            }

            if (this.isString(value)) {
                return 'viewer-type--string';
            }

            if (this.isNumber(value)) {
                return 'viewer-type--number';
            }

            if (this.isBoolean(value)) {
                return 'viewer-type--boolean';
            }

            if (null === value) {
                return 'viewer-type--null';
            }
        },

        reverseObj(object) {
            var NewObj = {},
                keysArr = Object.keys(object);
            for (var i = keysArr.length - 1; i >= 0; i--) {
                NewObj[keysArr[i]] = object[keysArr[i]];
            }
            return NewObj;
        },

        parser(obj) {
            return [
                {
                    text: this.name,
                    state: { expanded: true },
                    data: { type: 'root' },
                    children: this.map(obj, this.transformObject),
                },
            ];
        },
    },
};
</script>

<style scoped>
.json-viewer {
    width: 100%;
    height: 400px;
    overflow: auto;
}

.viewer-item__key {
    color: #303030;
}

.viewer-item__key,
.viewer-item__value {
    padding: 0 3px;
}

.viewer-type--boolean .viewer-item__value {
    color: #ff9b21;
}

.viewer-type--number .viewer-item__value {
    color: #f85441;
}

.viewer-type--null .viewer-item__value {
    color: #acabab;
}

.viewer-type--string .viewer-item__value {
    color: green;
}
</style>
