<template>
  <div>
  <!-- Form -->
<el-dialog title="新增权限" :visible.sync="bdialogFormVisible" :before-close="closeWin">
  <el-form :model="form">
    <el-form-item label="许可" :label-width="formLabelWidth">
      <el-input v-model="form.name" auto-complete="off"></el-input>
    </el-form-item>
    <el-form-item label="展示名称" :label-width="formLabelWidth">
      <el-input v-model="form.display_name" auto-complete="off"></el-input>
    </el-form-item>
    <el-form-item label="描述" :label-width="formLabelWidth">
      <el-input v-model="form.description" auto-complete="off"></el-input>
    </el-form-item>
  </el-form>
  <div slot="footer" class="dialog-footer">
    <el-button @click="handelCancel">取 消</el-button>
    <el-button type="primary" @click="handelSave" :loading="loading">确 定</el-button>
  </div>
</el-dialog>
</div>
</template>

<script>
    export default {
    props: ['getdialogFormVisible'],
    data() {
      return {
        form: {
          name:'',
          display_name:'',
          description:''
        },
        formLabelWidth: '120px',
        loading: false
      }
    },
    computed:{
      bdialogFormVisible:function(close){
        return this.getdialogFormVisible;
      }
    },
    methods: {
      handelSave(){
        this.loading=true;
        axios.post('permission', {
          form: this.form,
        })
        .then((response) =>{
           this.loading=false;
          if(response.data.status){
            this.bdialogFormVisible=false;
            this.$emit('datarefresh', response.data.data); //刷新数据
            this.$emit('increment', false); //关闭弹窗
            this.$message({
              message: '恭喜你，新增成功！',
              type: 'success'
            });
          }else{
            this.$message({
              message: response.data.msg,
              type: 'error'
            });
          }
        })
        .catch(function (error) {
          console.log(error);
          this.loading=false;
        });
      },
      handelCancel(){
        this.$emit('increment', false)
      },
      closeWin(done){
          this.$emit('increment', false)
      }
    }
  }
</script>
