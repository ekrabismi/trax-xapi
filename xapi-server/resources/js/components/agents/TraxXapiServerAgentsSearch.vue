<template>
    <form autocomplete="off" class="trax-form-with-icons" v-on:submit.prevent="search">
    
        <!-- Object Type -->

        <trax-ui-select icon="supervisor_account" :unselected="lang.trax_xapi_server.common.all_object_types" 
            v-model="form.objectType" :options="object_type_select">
        </trax-ui-select>

        <!-- ID Type -->

        <trax-ui-select icon="account_box" :unselected="lang.trax_xapi_server.common.all_id_types" 
            v-model="form.idType" :options="id_type_select">
        </trax-ui-select>

        <!-- Mbox -->

        <trax-ui-input type="text" icon="email" :placeholder="lang.trax_xapi_server.common.mbox"
            v-model="form.mbox" v-if="form.idType=='mbox'">
        </trax-ui-input>

        <!-- OpenId -->

        <trax-ui-input type="text" icon="contact_mail" :placeholder="lang.trax_xapi_server.common.openid"
            v-model="form.openid" v-if="form.idType=='openid'">
        </trax-ui-input>

        <!-- Account -->

        <trax-ui-input type="text" icon="vpn_key" :placeholder="lang.trax_xapi_server.common.account_homepage"
            v-model="form.account_homepage" v-if="form.idType=='account'">
        </trax-ui-input>

        <trax-ui-input type="text" icon="vpn_key" :placeholder="lang.trax_xapi_server.common.account_name"
            v-model="form.account_name" v-if="form.idType=='account'">
        </trax-ui-input>

        <!-- Name -->

        <trax-ui-input type="text" icon="text_fields" :placeholder="lang.trax_xapi_server.common.name"
            v-model="form.name">
        </trax-ui-input>


        <!-- Submit buttons -->

        <div class="trax-form-actions text-right">
            <button type="button" class="btn btn-default btn-link" @click="reset"> {{ lang.trax_xapi_server.common.reset }} </button>
            <button type="submit" class="btn btn-primary btn-round"> {{ lang.trax_xapi_server.common.search }} </button>
        </div>

    </form>
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
                form: {},
                object_type_select: [
                    {value: 'Agent', name: lang.trax_xapi_server.common.agent},
                    {value: 'Group', name: lang.trax_xapi_server.common.group},
                ],
                id_type_select: [
                    {value: 'mbox', name: lang.trax_xapi_server.common.mbox},
                    {value: 'mbox_sha1sum', name: lang.trax_xapi_server.common.mbox_sha1sum},
                    {value: 'openid', name: lang.trax_xapi_server.common.openid},
                    {value: 'account', name: lang.trax_xapi_server.common.account},
                ],
            }
        },

        computed: {

            idType() {
                return this.form.idType;
            }
        },

        watch: {

            idType() {
                this.form.mbox = null;
                this.form.openid = null;
                this.form.account_homepage = null;
                this.form.account_name = null;
            }
        },

        methods: {

            search() {
                this.bus.$emit(this.id+'-refresh', this.form);
                this.bus.$emit(this.id+'-search-close');
            },

            reset() {
                this.form = {};
                this.search();
            }
        }
            
    }
</script>
