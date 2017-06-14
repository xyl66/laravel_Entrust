<template>
  <div>
    <button type="button" class="btn btn-link " @click="dialogFormVisibleCreate=true"><span class="glyphicon glyphicon-plus"></span>新增角色</button>
    <el-table
    :data="tableData"
    border
    style="width: 100%">
    <el-table-column
      label="角色"
      width="180">
      <template scope="scope">
        <el-popover trigger="hover" placement="left-start">
          <p>名称: {{ scope.row.name }}</p>
          <p>描述: {{ scope.row.description }}</p>
          <el-tag slot="reference">{{ scope.row.display_name }}</el-tag>
        </el-popover>
      </template>
    </el-table-column>
    <el-table-column
      label="权限"
      min-width="180">
      <template scope="scope">
        <span style="margin-left: 10px" v-for="todo in scope.row.permissions">
        <el-popover
          placement="top"
          title="角色详细信息"
          width="200"
          trigger="focus">
          <p>名称: {{ todo.name }}</p>
          <p>描述: {{ todo.description }}</p>
          <el-tag slot="reference" type="primary">{{ todo.display_name }}</el-tag>
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
<my-component v-on:increment="changeShow" v-on:datarefresh="dataRefresh" :getdialogFormVisible="dialogFormVisibleCreate" :getallpermissions="allpermissions"></my-component>
<el-dialog title="角色编辑" :visible.sync="dialogFormVisible">
  <el-form :model="form">
    <el-form-item label="名称" :label-width="formLabelWidth">
      <el-input v-model="form.name" auto-complete="off" :disabled="form.name==='admin'?true:false"></el-input>
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
        <el-checkbox v-for="permission in allpermissions" :label="permission.id" :key="permission.display_name">{{permission.display_name}}</el-checkbox>
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
    props: ['initialtable','allpermissions'],
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
        loading: false
      }
    },
    components: {
      // <my-component> 将只在父模板可用
      'my-component': Create
    },
    computed:{
      allid:function (){
        let allid=[];
        for (var i = 0; i < this.allpermissions.length; i++) {
          allid.push(this.allpermissions[i].id);
        }
        return allid;
      }
    },
    methods: {
      handleEdit(index, row) {
        this.dialogFormVisible = true; //显示弹出框
        this.form=row;
        this.checkedCities=null;
        this.checkedCities=[];
        for (var i = 0; i < row.permissions.length; i++) {
          this.checkedCities.push(row.permissions[i].id);
        }
        let checkedCount = this.checkedCities.length;
        this.checkAll = checkedCount === this.allpermissions.length;
        this.isIndeterminate = checkedCount > 0 && checkedCount < this.allpermissions.length;
      },
      handleDelete(index, row) {
        console.log(index, row);
        axios.delete('role/'+row.id, {
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
      handleCheckAllChange(event) {
        this.checkedCities = event.target.checked ? this.allid : [];
        console.log(this.checkedCities);
        this.isIndeterminate = false;
      },
      handleCheckedCitiesChange(value) {
        console.log(value);
        let checkedCount = value.length;
        this.checkAll = checkedCount === this.allpermissions.length;
        this.isIndeterminate = checkedCount > 0 && checkedCount < this.allpermissions.length;
      },
      handelSave(){
        this.loading=true;
        axios.put('role', {
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
      }
    }
  }
</script>
