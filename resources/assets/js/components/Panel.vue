<template>
    <div class="panel panel-default">
        <div class="panel-heading" :class="type">
            <h4 class="panel-title">
                <a data-toggle="collapse" href="#ac1-2-1" :aria-expanded="expanded" :class="collapsedClass">
                    <slot name="heading"></slot>
                </a>
            </h4>
        </div>
        <div id="ac1-2-1" class="panel-collapse" :class="expandedClass" :aria-expanded="expanded">
            <div class="panel-body">
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
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
        }
    }
</script>
