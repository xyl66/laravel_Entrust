<template>
  <div>
    <button type="button" class="btn btn-link " @click="dialogFormVisibleCreate=true"><span class="glyphicon glyphicon-plus"></span>  新增权限</button>
    <el-table
    :data="tableData"
    border
    style="width: 100%">
    <el-table-column
      label="权限"
      width="180"
      :default-sort = "{prop: 'updated_at', order: 'descending'}">
      <template scope="scope">
        <el-popover trigger="hover" placement="left-start">
          <p>名称: {{ scope.row.display_name }}</p>
          <p>描述: {{ scope.row.description }}</p>
          <el-tag slot="reference">{{ scope.row.display_name }}</el-tag>
        </el-popover>
      </template>
    </el-table-column>
    <el-table-column
      prop="name"
      label="许可"
      sortable
      width="180">
    </el-table-column>
    <el-table-column
      prop="updated_at"
      label="修改日期"
      sortable
      width="180">
    </el-table-column>
    <el-table-column label="操作">
      <template scope="scope">
        <el-button
          size="small"
          @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
        <el-button
          size="small"
          type="danger"
          @click="handleDelete(scope.$index, scope.row)">删除</el-button>
      </template>
    </el-table-column>
  </el-table>
  <!-- Form -->
<my-component v-on:increment="changeShow" v-on:datarefresh="dataRefresh" :getdialogFormVisible="dialogFormVisibleCreate"></my-component>
<el-dialog title="用户组编辑" :visible.sync="dialogFormVisible">
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
    import CreatePermission from "./CreatePermission.vue";
    export default {
    props: ['initialtable'],
    data() {
      return {
        tableData: this.initialtable,
        dialogFormVisible: false,
        dialogFormVisibleCreate:false,
        form: {
          permissions:[]
        },
        formLabelWidth: '120px',
        loading: false
      }
    },
    components: {
      // <my-component> 将只在父模板可用
      'my-component': CreatePermission
    },
    computed:{
      
    },
    methods: {
      handleEdit(index, row) {
        this.dialogFormVisible = true; //显示弹出框
        this.form=row;
      },
      handleDelete(index, row) {
        console.log(index, row);
        axios.delete('permission/'+row.id, {
        })
        .then((response) =>{
          if(response.data.status){
            this.tableData=response.data.data; //刷新数据
            this.$message({
              message: '恭喜你，删除成功！',
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
        });
      },
      handelSave(){
        this.loading=true;
        axios.put('permission', {
          form: this.form,
        })
        .then((response) =>{
           this.loading=false;
          if(response.data.status){
            this.dialogFormVisible=false;
            this.tableData=response.data.data; //刷新数据
            this.$message({
              message: '恭喜你，修改成功！',
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
        });
      },
      handelCancel(){
        this.loading = false;
        this.dialogFormVisible=false;
      },
      changeShow(val){
        this.dialogFormVisibleCreate=val;
      },
      dataRefresh(val){  //刷新数据
        this.tableData=val;
      }
    }
  }
</script>
