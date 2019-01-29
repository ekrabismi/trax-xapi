<template>
    <form autocomplete="off" class="trax-form-with-icons" v-on:submit.prevent="search">
   
        <trax-ui-vertical-tabs :tabs="tabs" left-col-style="margin-top:10px;" right-col-style="margin-top:-10px;">

            <!-- ------------------------------------ ACTOR --------------------------------------- -->

            <template slot="tab-1">

                <!-- Object Type -->

                <trax-ui-select icon="supervisor_account" :unselected="lang.trax_xapi_server.common.all_object_types" 
                    v-model="form.actor.objectType" :options="actor_object_type_select">
                </trax-ui-select>

                <!-- Mbox -->

                <trax-ui-input type="text" icon="email" :placeholder="lang.trax_xapi_server.common.mbox_just_email"
                    v-model="form.actor.mbox">
                </trax-ui-input>

                <!-- OpenId -->

                <trax-ui-input type="text" icon="contact_mail" :placeholder="lang.trax_xapi_server.common.openid"
                    v-model="form.actor.openid">
                </trax-ui-input>

                <!-- Account -->

                <trax-ui-input type="text" icon="vpn_key" :placeholder="lang.trax_xapi_server.common.account_homepage"
                    v-model="form.actor.account_homepage">
                </trax-ui-input>

                <trax-ui-input type="text" icon="vpn_key" :placeholder="lang.trax_xapi_server.common.account_name"
                    v-model="form.actor.account_name">
                </trax-ui-input>

                <!-- Related Agents -->

                <trax-ui-checkbox :text="lang.trax_xapi_server.common.include_object" v-model="form.actor.include_object">
                </trax-ui-checkbox>

            </template>

            <!-- ------------------------------------ VERB --------------------------------------- -->

            <template slot="tab-2">

                <!-- ID -->

                <trax-ui-input type="text" icon="http" :placeholder="lang.trax_xapi_server.common.id"
                    v-model="form.verb.id">
                </trax-ui-input>

            </template>
            
            <!-- ------------------------------------ OBJECT --------------------------------------- -->

            <template slot="tab-3">

                <!-- Object Type -->

                <trax-ui-select icon="video_library" :unselected="lang.trax_xapi_server.common.all_object_types" 
                    v-model="form.object.objectType" :options="object_object_type_select">
                </trax-ui-select>

                <!-- Activity ID -->

                <trax-ui-input type="text" icon="http" :placeholder="lang.trax_xapi_server.common.id"
                    v-model="form.object.id" v-if="form.object.objectType=='Activity'">
                </trax-ui-input>

                <!-- Activity Type -->

                <trax-ui-input type="text" icon="http" :placeholder="lang.trax_xapi_server.common.type"
                    v-model="form.object.type" v-if="form.object.objectType=='Activity'">
                </trax-ui-input>

                <!-- Related Activities -->

                <trax-ui-checkbox :text="lang.trax_xapi_server.common.include_context" 
                    v-model="form.object.include_context" v-if="form.object.objectType=='Activity'">
                </trax-ui-checkbox>

                <!-- Agent Mbox -->

                <trax-ui-input type="text" icon="email" :placeholder="lang.trax_xapi_server.common.mbox_just_email"
                    v-model="form.object.mbox" v-if="form.object.objectType=='Agent' || form.object.objectType=='Group'">
                </trax-ui-input>

                <!-- Agent Open ID -->

                <trax-ui-input type="text" icon="contact_mail" :placeholder="lang.trax_xapi_server.common.openid"
                    v-model="form.object.openid" v-if="form.object.objectType=='Agent' || form.object.objectType=='Group'">
                </trax-ui-input>

                <!-- Agent Account Homepage -->

                <trax-ui-input type="text" icon="vpn_key" :placeholder="lang.trax_xapi_server.common.account_homepage"
                    v-model="form.object.account_homepage" v-if="form.object.objectType=='Agent' || form.object.objectType=='Group'">
                </trax-ui-input>

                <!-- Agent Account Homepage -->

                <trax-ui-input type="text" icon="vpn_key" :placeholder="lang.trax_xapi_server.common.account_name"
                    v-model="form.object.account_name" v-if="form.object.objectType=='Agent' || form.object.objectType=='Group'">
                </trax-ui-input>

            </template>
            
            <!-- ------------------------------------ RESULT --------------------------------------- -->

            <template slot="tab-4">

                <!-- Completion -->

                <trax-ui-select icon="assignment_turned_in" :unselected="lang.trax_xapi_server.common.completion_all" 
                    v-model="form.result.completion" :options="result_completion_select">
                </trax-ui-select>

                <!-- Success -->

                <trax-ui-select icon="school" :unselected="lang.trax_xapi_server.common.success_all" 
                    v-model="form.result.success" :options="result_success_select">
                </trax-ui-select>

                <!-- Score -->

                <trax-ui-select icon="grade" :unselected="lang.trax_xapi_server.common.score_all" 
                    v-model="form.result.score_type" :options="result_score_type_select">
                </trax-ui-select>

                <!-- Score Operator -->

                <trax-ui-input type="text" icon="chevron_right" :placeholder="lang.trax_xapi_server.common.operator_help"
                    v-model="form.result.score_operator" v-if="form.result.score_type=='raw'||form.result.score_type=='scaled'">
                </trax-ui-input>

                <!-- Score Value -->

                <trax-ui-input type="text" icon="chevron_right" :placeholder="lang.trax_xapi_server.common.value"
                    v-model="form.result.score_value" v-if="form.result.score_type=='raw'||form.result.score_type=='scaled'">
                </trax-ui-input>

            </template>

            <!-- ------------------------------------ CONTEXT --------------------------------------- -->

            <template slot="tab-5">

                <!-- Activity ID -->

                <trax-ui-input type="text" icon="http" :placeholder="lang.trax_xapi_server.common.activity_id" 
                    v-model="form.context.activity_id">
                </trax-ui-input>

                <!-- Activity Relation -->

                <trax-ui-select icon="link" :unselected="lang.trax_xapi_server.common.all_relations" 
                    v-model="form.context.activity_relation" :options="context_activity_relation_select">
                </trax-ui-select>

                <!-- Registration ID -->

                <trax-ui-input type="text" icon="input" :placeholder="lang.trax_xapi_server.common.registration" 
                    v-model="form.context.registration_id">
                </trax-ui-input>

            </template>

            <!-- ------------------------------------ OTHERS --------------------------------------- -->

            <template slot="tab-6">

                <!-- Statement ID -->

                <trax-ui-input type="text" icon="local_offer" :placeholder="lang.trax_xapi_server.common.statement_id"
                    v-model="form.statementId">
                </trax-ui-input>

                <!-- Voided -->

                <trax-ui-select icon="delete" :unselected="lang.trax_xapi_server.common.all_statements" 
                    v-model="form.voided" :options="voided_select">
                </trax-ui-select>

                <!-- Attachments -->

                <trax-ui-select icon="attachment" :unselected="lang.trax_xapi_server.common.all_statements" 
                    v-model="form.attachments" :options="attachments_select">
                </trax-ui-select>

                <!-- Timestamp -->

                <trax-ui-datetime icon="today" :placeholder="lang.trax_xapi_server.common.timestamp_since" iso="1"
                    v-model="form.timestamp_since">
                </trax-ui-datetime>

                <trax-ui-datetime icon="event" :placeholder="lang.trax_xapi_server.common.to" iso="1"
                    v-model="form.timestamp_until">
                </trax-ui-datetime>

                <!-- Stored -->

                <trax-ui-datetime icon="today" :placeholder="lang.trax_xapi_server.common.stored_since" iso="1"
                    v-model="form.stored_since">
                </trax-ui-datetime>

                <trax-ui-datetime icon="event" :placeholder="lang.trax_xapi_server.common.to" iso="1"
                    v-model="form.stored_until">
                </trax-ui-datetime>

            </template>

        </trax-ui-vertical-tabs>


        <!-- Submit buttons -->

        <div class="trax-form-actions text-right">
            <button type="button" class="btn btn-default btn-link" @click="resetAll"> {{ lang.trax_xapi_server.common.reset_all }} </button>
            <button type="button" class="btn btn-default btn-link" @click="resetAllClose"> {{ lang.trax_xapi_server.common.reset_all_and_close }} </button>
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
                tabs: [
                    { value: 'actor', name: lang.trax_xapi_server.common.actor },
                    { value: 'verb', name: lang.trax_xapi_server.common.verb },
                    { value: 'object', name: lang.trax_xapi_server.common.object },
                    { value: 'result', name: lang.trax_xapi_server.common.result },
                    { value: 'context', name: lang.trax_xapi_server.common.context },
                    { value: 'others', name: lang.trax_xapi_server.common.others }
                ],
                actor_object_type_select: [
                    {value: 'Agent', name: lang.trax_xapi_server.common.agent},
                    {value: 'Group', name: lang.trax_xapi_server.common.group},
                ],
                object_object_type_select: [
                    {value: 'Activity', name: lang.trax_xapi_server.common.activity},
                    {value: 'Agent', name: lang.trax_xapi_server.common.agent},
                    {value: 'Group', name: lang.trax_xapi_server.common.group},
                    {value: 'StatementRef', name: lang.trax_xapi_server.common.statement_ref},
                    {value: 'SubStatement', name: lang.trax_xapi_server.common.sub_statement},
                ],
                result_completion_select: [
                    {value: 'completed', name: lang.trax_xapi_server.common.completed},
                    {value: 'incomplete', name: lang.trax_xapi_server.common.incomplete},
                    {value: 'defined', name: lang.trax_xapi_server.common.defined},
                    {value: 'undefined', name: lang.trax_xapi_server.common.undefined},
                ],
                result_success_select: [
                    {value: 'passed', name: lang.trax_xapi_server.common.passed},
                    {value: 'failed', name: lang.trax_xapi_server.common.failed},
                    {value: 'defined', name: lang.trax_xapi_server.common.defined},
                    {value: 'undefined', name: lang.trax_xapi_server.common.undefined},
                ],
                result_score_type_select: [
                    {value: 'raw', name: lang.trax_xapi_server.common.score_raw},
                    {value: 'scaled', name: lang.trax_xapi_server.common.score_scaled},
                    {value: 'defined', name: lang.trax_xapi_server.common.defined},
                    {value: 'undefined', name: lang.trax_xapi_server.common.undefined},
                ],
                context_activity_relation_select: [
                    {value: 'parent', name: lang.trax_xapi_server.common.parent},
                    {value: 'grouping', name: lang.trax_xapi_server.common.grouping},
                    {value: 'category', name: lang.trax_xapi_server.common.category},
                    {value: 'other', name: lang.trax_xapi_server.common.other},
                ],
                voided_select: [
                    {value: 'unvoided', name: lang.trax_xapi_server.common.unvoided},
                    {value: 'voided', name: lang.trax_xapi_server.common.voided},
                ],
                attachments_select: [
                    {value: 'with', name: lang.trax_xapi_server.common.with_attachments},
                    {value: 'without', name: lang.trax_xapi_server.common.without_attachments},
                ],
            }
        },

        computed: {

            objectObjectType() {
                if (!this.form.object) return;
                return this.form.object.objectType;
            },

            resultScoreType() {
                if (!this.form.result) return;
                return this.form.result.score_type;
            }
        },

        watch: {

            objectObjectType() {
                if (!this.form.object) return;
                this.form.object.id = null;
                this.form.object.type = null;
                this.form.object.include_context = null;
                this.form.object.mbox = null;
                this.form.object.openid = null;
                this.form.object.account_homepage = null;
                this.form.object.account_name = null;
            },

            resultScoreType() {
                if (!this.form.result) return;
                this.form.result.score_operator = null;
                this.form.result.score_value = null;
            }
        },

        created: function() {
            this.clearData();
        },

        methods: {

            clearData() {
                this.form = {
                    actor: {},
                    verb: {},
                    object: {},
                    result: {},
                    context: {}
                };
            },

            resetAll() {
                this.clearData();
            },

            resetAllClose() {
                this.clearData();
                this.search();
            },

            search() {
                this.bus.$emit(this.id+'-refresh', this.form);
                this.bus.$emit(this.id+'-search-close');
            }
        }
    }
</script>
