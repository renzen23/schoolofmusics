<template>
    <div class="grid-card cols">
        <div class="row no-gutters inner-wrapper">

            <a class="image-link" :href=item.link></a>

            <div class="image">
                <div class="image-wrapper" :style="backgroundImage"></div>
            </div>


            <div class="content">
                <a class="cta">
                    <div class="icon">
                    </div>
                    <span>{{ item.title.rendered }}</span>
                </a>
            </div>
        </div>
    </div>


</template>

<script>
import Arrow from "../../../../assets/images/arrow.svg";
import moment from "moment/src/moment";

export default {
    name: "EnsembleCard",
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
            if( this.item.acf.options === 'alternative' && this.item.acf.alternative_thumb.sizes.medium_large.length > 0 ) {
              let image = this.item.acf.alternative_thumb.sizes.medium_large;
              output = "background-image: url(" + image + ");";
            } else {
              this.item.acf.sections.find((o, i) => {
                  if( o.acf_fc_layout === 'hero' ) {
//                      let image = this.item.acf.sections[i].image.sizes.medium_large;
                      let image = this.item.acf.sections[i].image.url;
                      output = "background-image: url(" + image + ");";
                  }
              });
            }
            return output;
        },
        ensemblesCategory() {
            return this.item.ensembles_category.length > 0 ? this.item.ensembles_category[0] : "";
        },

    },
    created() {

    },
    destroy() {

    },
    filters: {
        relativeDate: function(date) {
            return moment(date, "YYYYMMDD").fromNow();
        },
    }
};
</script>
