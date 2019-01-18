<template>

    <trax-ui-ajax-table-with-actions :id="id" :titles="titles" :actions="actions"  
        :props="props" :bus="bus" :endpoint="endpoint" order-column="0" order-dir="desc">
    </trax-ui-ajax-table-with-actions>

</template>

<script>
    export default {
    
        props: {
            id: null,
            bus: null
        },
        
        data: function() {
            return {
                lang: lang,
                titles: [
                    '',
                    lang.trax_xapi_server.common.id,
                    lang.trax_xapi_server.common.name,
                    lang.trax_xapi_server.common.type,
                    lang.trax_xapi_server.common.more,
                    lang.trax_xapi_server.common.ext,
                    ''
                ],
                actions: [
                    {name: 'json', icon: 'code', class: 'btn-primary', event: 'json'}
                ],
                endpoint: app_url+'trax/ajax/xapi-server/activities',
                props: [
                    {source: 'updated_at', visible: false },
                    {source: this.renderId, orderable: false },
                    {source: this.renderName, orderable: false },
                    {source: this.renderType, orderable: false },
                    {source: this.renderMore, orderable: false },
                    {source: this.renderExt, orderable: false },
                ]
            }
        },
        
        created: function() {
            this.bus.$on(this.id+'-json', this.openJson);
        },
        
        methods: {
        
            // Actions

            openJson(data) {
                this.bus.$emit(this.id+'-json-open', {data: data.data});
            },

            // Columns rendering

            renderId(data, type, row, meta) {
                data = row.data;
                if (!data.id) return '';
                return '<i class="material-icons trax-text-small mr-1">play_circle_filled</i> '+this.compactURI(data.id);
            },

            renderName(data, type, row, meta) {
                data = row.data;
                if (!data.definition || !data.definition.name) return '';
                return this.langString(data.definition.name);
            },

            renderType(data, type, row, meta) {
                data = row.data;
                if (!data.definition || !data.definition.type) return '';
                return this.twoLinesURI(data.definition.type);
            },

            renderMore(data, type, row, meta) {
                data = row.data;
                if (!data.definition || !data.definition.moreInfo) return '';
                return '<a href="'+data.definition.moreInfo+'" class="btn btn-primary btn-link" target="_blank"><i class="material-icons">open_in_new</i></a>';

            },

            renderExt(data, type, row, meta) {
                data = row.data;
                if (!data.definition || !data.definition.extensions) return '';
                return Object.keys(data.definition.extensions).length;
            },


            // Utilities

            langString(data) {
                var keys = Object.keys(data);
                if (keys.length == 0) return '';
                for (var i=0; i<keys.length; i++) {
                    if (keys[i].indexOf(window.locale) !== -1) return data[keys[i]];
                }
                return data[keys[0]];
            },

            compactURI(uri) {
                var max = 35;
                if (uri.length <= max) return uri;
                var parts = uri.split('/');
                uri = parts[0]+'//'+parts[2]+'/.../'+parts[parts.length-1];
                if (uri.length <= max) return uri;
                return uri.substring(0, max-3)+'...';
            },

            twoLinesURI(uri) {
                var parts = uri.split('/');
                return '<strong>'+parts[parts.length-1]+'</strong><br><small>'+parts[2]+'</small>';
            }

        }
    }

</script>
