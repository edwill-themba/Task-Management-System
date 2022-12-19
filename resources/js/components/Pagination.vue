<template>
    <div>
        <div class="tasks-pagination">
              <fontAwsomeIcon icon="angle-double-left" class="icon" @click="changePages(0)"/>
               <fontAwsomeIcon icon="angle-left" class="icon"  @click="changePages(-1)" />
                 <span >showing page {{ page }} of {{ pages }} pages</span> 
               <fontAwsomeIcon  icon="angle-right" class="icon" @click="changePages(1)" />
            <fontAwsomeIcon  icon="angle-double-right" class="icon"  @click="changePages(2)"/>
        </div>
    </div>
</template>

<script>
export default {
  name: "Pagination",
  props: ["totalRecords", "perPage"],
  data() {
    return {
      page: 1
    };
  },
  computed: {
    // gets the number of pages
    pages() {
      //  this.totalRecords = this.tasks.length;
      let remainder = this.totalRecords % this.perPage;
      if (remainder > 0) {
        return Math.floor(this.totalRecords / this.perPage) + 1;
      } else {
        return this.totalRecords / this.perPage;
      }
    }
  },
  methods: {
    changePages(val) {
      switch (val) {
        case 0:
          this.page = 1;
          break;
        case -1:
          this.page > 1 ? (this.page = this.page - 1) : this.page;
          break;
        case 1:
          this.page < this.pages ? (this.page = this.page + 1) : this.pages;
          break;
        case 2:
          this.page = this.pages;
          break;
        default:
          this.page = 0;
          break;
      }
      this.$emit("changePage", this.page);
    }
  }
};
</script>

<style scoped>
.tasks-pagination span {
  margin: auto 15px;
  font-weight: 500;
}
.icon {
  color: #111;
  cursor: pointer;
}
.icon:hover {
  color: coral;
}
</style>
