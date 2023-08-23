<template>
    <div class="col-md-4 grid-card cols">
        <div class="inner-wrapper">

            <a class="image-link" :href=item.link></a>

            <div class="image">
                <div class="image-wrapper" :style="backgroundImage"></div>
            </div>


            <div class="content">
                <a class="cta">
                    <span>{{ item.title.rendered }}</span>
                </a>
                <!--<span class="department-name">{{ item.department_name.join(', ') }}</span>-->
                <span class="department-name">{{ item.acf.title }}</span>
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
        showCategoriesOnCard: {
            type: Boolean,
        },
    },
    methods: {

    },
    computed: {
        backgroundImage() {
            let output = "";
            if( typeof this.item.acf.portrait === 'object' ) {
                let image = this.item.acf.portrait.sizes.large;
                output = "background-image: url(" + image + ");";
            }
            return output;
        },

    },
    created() {

    },
    destroy() {

    },
    filters: {

    }
};
</script>
