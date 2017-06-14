<template>
  <div>
    <el-table
    :data="tableData"
    border
    style="width: 100%">
    <el-table-column
        prop="name"
        label="用户名"
        sortable
        width="120">
        <template scope="scope">
        <el-popover
          effect="dark"
          placement="left-start"
          title="用户详细信息"
          width="200"
          trigger="hover">
          <p>名称: {{ scope.row.name }}</p>
          <p>创建时间: {{ scope.row.created_at }}</p>
          <p>修改时间: {{ scope.row.updated_at }}</p>
          <el-tag slot="reference">{{ scope.row.name }}</el-tag>
        </el-popover>
      </template>
    </el-table-column>
    <el-table-column
        prop="email"
        label="邮箱"
        sortable
        width="200"
        :show-overflow-tooltip="true">
    </el-table-column>
    <el-table-column
      prop="roles"
      label="角色"
      width="240"
      :filters="allfilters"
      :filter-method="handelFilterTag">
      <template scope="scope">
        <span style="margin-left: 10px" v-for="todo in scope.row.roles">
        <el-popover
          placement="top"
          title="角色详细信息"
          width="200"
          trigger="focus">
          <p>名称: {{ todo.name }}</p>
          <p>描述: {{ todo.description }}</p>
          <el-tag slot="reference" :type="todo.name==='admin'?'danger':(todo.name==='developer'?'warning':'primary')">{{ todo.display_name }}</el-tag>
        </el-popover>
        </span>
      </template>
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
<my-component v-on:increment="changeShow" v-on:datarefresh="dataRefresh" :getdialogFormVisible="dialogFormVisibleCreate" :getallpermissions="allroles"></my-component>
<el-dialog title="用户编辑" :visible.sync="dialogFormVisible">
  <el-form :model="form">
    <el-form-item label="用户名" :label-width="formLabelWidth">
      <el-input v-model="form.name" auto-complete="off"></el-input>
    </el-form-item>
    <el-form-item label="邮箱" :label-width="formLabelWidth">
      <el-input v-model="form.email" :disabled="true" auto-complete="off"></el-input>
    </el-form-item>
    <el-form-item label="权限">
      <el-checkbox :indeterminate="isIndeterminate" v-model="checkAll" @change="handleCheckAllChange">全选</el-checkbox>
      <div style="margin: 15px 0;"></div>
      <el-checkbox-group v-model="checkedCities" @change="handleCheckedCitiesChange">
        <el-checkbox v-for="role in allroles" :label="role.id" :key="role.display_name">{{role.display_name}}</el-checkbox>
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
    import Create from "./Create.vue";
    export default {
    props: ['initialtable','allroles'],
    data() {
      return {
        tableData: this.initialtable,
        dialogFormVisible: false,
        dialogFormVisibleCreate:false,
        form: {
          permissions:[]
        },
        formLabelWidth: '120px',
        checkAll: true,
        checkedCities: [],
        isIndeterminate: true,
        loading: false,
        allfilters:[]
      }
    },
    components: {
      // <my-component> 将只在父模板可用
      'my-component': Create
    },
    mounted(){
      let allfilters=[];
        for (var i = 0; i < this.allroles.length; i++) {
          let arr={'text':this.allroles[i].display_name,'value':this.allroles[i].id};
          allfilters.push(arr);
        }
        this.allfilters=allfilters;
    },
    computed:{
      allid:function (){
        let allid=[];
        for (var i = 0; i < this.allroles.length; i++) {
          allid.push(this.allroles[i].id);
        }
        return allid;
      },
    },
    methods: {
      handelFilterTag(value,row) { //筛选数据
        for (var i = 0; i < row.roles.length; i++) {
          if(row.roles[i].id===value){
            return true;
          };
        }
        return false;
      },
      handleEdit(index, row) {
        this.dialogFormVisible = true; //显示弹出框
        this.form=row;
        this.checkedCities=null;
        this.checkedCities=[];
        for (var i = 0; i < row.roles.length; i++) {
          this.checkedCities.push(row.roles[i].id);
        }
        let checkedCount = this.checkedCities.length;
        this.checkAll = checkedCount === this.allroles.length;
        this.isIndeterminate = checkedCount > 0 && checkedCount < this.allroles.length;
      },
      handleDelete(index, row) {
        console.log(index, row);
        /*axios.delete('user/'+row.id, {
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
          console.log(error.message);
        });*/
      },
      handleCheckAllChange(event) {
        this.checkedCities = event.target.checked ? this.allid : [];
        console.log(this.checkedCities);
        this.isIndeterminate = false;
      },
      handleCheckedCitiesChange(value) {
        console.log(value);
        let checkedCount = value.length;
        this.checkAll = checkedCount === this.allroles.length;
        this.isIndeterminate = checkedCount > 0 && checkedCount < this.allroles.length;
      },
      handelSave(){
        this.loading=true;
        axios.put('user', {
          form: this.form,
          checkedCities: this.checkedCities
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
      },
    }
  }
</script>
