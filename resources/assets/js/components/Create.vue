<template>
  <div>
  <!-- Form -->
<el-dialog title="新增角色" :visible.sync="bdialogFormVisible" :before-close="closeWin">
  <el-form :model="form">
    <el-form-item label="名称" :label-width="formLabelWidth">
      <el-input v-model="form.name" auto-complete="off"></el-input>
    </el-form-item>
    <el-form-item label="展示名称" :label-width="formLabelWidth">
      <el-input v-model="form.display_name" auto-complete="off"></el-input>
    </el-form-item>
    <el-form-item label="描述" :label-width="formLabelWidth">
      <el-input v-model="form.description" auto-complete="off"></el-input>
    </el-form-item>
    <el-form-item label="权限">
      <el-checkbox :indeterminate="isIndeterminate" v-model="checkAll" @change="handleCheckAllChange">全选</el-checkbox>
      <div style="margin: 15px 0;"></div>
      <el-checkbox-group v-model="checkedCities" @change="handleCheckedCitiesChange">
        <el-checkbox v-for="permission in getallpermissions" :label="permission.id" :key="permission.display_name">{{permission.display_name}}</el-checkbox>
      </el-checkbox-group>
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
    props: ['getdialogFormVisible','getallpermissions'],
    data() {
      return {
        form: {
          name:'',
          display_name:'',
          description:''
        },
        formLabelWidth: '120px',
        checkAll: false,
        checkedCities: [],
        isIndeterminate: false,
        loading: false
      }
    },
    computed:{
      allid:function (){
        let allid=[];
        for (var i = 0; i < this.getallpermissions.length; i++) {
          allid.push(this.getallpermissions[i].id);
        }
        return allid;
      },
      bdialogFormVisible:function(close){
        return this.getdialogFormVisible;
      }
    },
    methods: {
      handleCheckAllChange(event) {
        this.checkedCities = event.target.checked ? this.allid : [];
        console.log(this.checkedCities);
        this.isIndeterminate = false;
      },
      handleCheckedCitiesChange(value) {
        console.log(value);
        let checkedCount = value.length;
        this.checkAll = checkedCount === this.getallpermissions.length;
        this.isIndeterminate = checkedCount > 0 && checkedCount < this.getallpermissions.length;
      },
      handelSave(){
        this.loading=true;
        axios.post('role', {
          form: this.form,
          checkedCities: this.checkedCities
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
