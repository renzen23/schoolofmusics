<template>
    <div class="accordion cols">
        <div class="header collapsed" :id="triggerId" data-toggle="collapse" :data-target="collapseIdId" aria-expanded="false" >
            <div class="year">{{ item.acf.year }}</div>
            <div class="title" v-html="item.title.rendered"></div>
            <div class="icon">
                <img src="/app/themes/ucla/resources/assets/images/chevron.svg" width="16px" height="16px" class="svg" alt="dropdown" />
                <div class="sr-only">dropdown</div>
            </div>
        </div>
        <div :id="collapseId" class="content collapse" :aria-labelledby="triggerId">
            <div class="row no-gutters inner-wrapper">
                <div class="text" v-html="description"></div>
                <div class="details">
                    <div class="program" v-html="program"></div>
                    <div class="author" v-html="authors"></div>

                    <a :href="linkUrl" v-if="linkUrl" class="cta circle-cta blue" target="_blank">
                         <div class="icon">
                             <img src="/app/themes/ucla/resources/assets/images/arrow.svg" width="16px" height="16px" class="svg" alt="Learn More" />
                             <div class="sr-only">Learn More</div>
                         </div>
                        <span>Learn More</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>


<script>

import Arrow from "../../../../assets/images/arrow.svg";
import moment from "moment/src/moment";

export default {
    name: "FacultyCard",
    components: {
        Arrow,
    },
    data() {
        return {

        }
    },
    props: {
        item: {
            type: Object,
        },
    },
    methods: {
    },
    computed: {
        triggerId: function() {
            return "header"+this.item.id;
        },
        collapseId: function() {
            return "collapse"+this.item.id;
        },
        // would like to reuse collapseId
        collapseIdId: function() {
            return "#collapse"+this.item.id;
        },
        description: function() {
            return this.item.acf.description;
        },
        program: function() {
            let str = this.item.category;
            return str;
        },
        authors: function() {
            let string = "";
            if (this.item.acf.authors) {
                this.item.acf.authors.forEach(function (element, index) {
                    if (index != 0) {
                        string += ", <br>";
                    }
                    string += element.post_title;
                });
            } else if (this.item.acf.alumni_author) {
                string = this.item.acf.alumni_author;
            }
            return string;
        },
        linkUrl: function() {
            return this.item.acf.link;
        }
    },
    created() {
    },
    destroy() {
    },
    filters: {
    }
};
</script>
