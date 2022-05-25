<template>
    <div class="panel panel-default" style="position: relative">
        <div class="panel-heading" :class="type">
            <h4 class="panel-title">
                <a data-toggle="collapse" :href="`#${id}`" :aria-expanded="expanded" :class="collapsedClass">
                    <slot name="heading"></slot>
                </a>
            </h4>
        </div>
        <div :id="id" class="panel-collapse" :class="expandedClass" :aria-expanded="expanded">
            <div class="panel-body">
                <slot></slot>
            </div>
        </div>
        <div class="dropzone-container" style="display: none;">
            <form id="dropzone" action="/assets" method="POST" class="dropzone" style="position: absolute; top: 0; left: 0; bottom: 0; right: 0; z-index: 5; background-color: #fff; margin: 1px; display: flex; flex-direction: column; justify-content: space-around;">
                <slot name="message">
                    <div class="dz-message">
                        <h3>Drag and drop files here</h3><span class="note">(Maximum file size of 25MB allowed. Maximum of 20 uploads per drop.)</span>
                    </div>
                </slot>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                id: null
            }
        },
        props: {
            type: {
                default: 'default',
                type: String
            },
            expanded: {
                default: 'true',
                type: String
            }
        },
        computed: {
            expandedClass: function () {
                return this.expanded === 'true' ? 'in' : 'collapse'
            },
            collapsedClass: function () {
                return this.expanded === 'true' ? '' : 'collapsed'
            }
        }, 
        mounted () {
            this.id = this._uid
        }
    }
</script>
