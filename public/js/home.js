var tableRow = null;
var box_info = null;
$('#indextable').bootstrapTable({
  height: 280,
  showColumns: true,
  showExport: true,
  search: true,
  exportDataType: 'all',
  searchTimeOut: 700,
  align: 'center',
  columns: [
    {
      title: '设备名称',
      field: 'name',
      sortable: true,
    },{
      title: '设备id',
      field: 'box_id',
      sortable: true,
      formatter: function(value){
        return parseInt(value).toString(16)
      }
    },{
      title: '用户',
      field: 'project_name',
      sortable: true,
    },{
      title: '省',
      field: 'province',
      sortable: true,
    },{
      title: '市',
      field: 'city',
      sortable: true,
    },{
      title: '地址',
      field: 'address',
      sortable: true,
    },{
      title: '负责人',
      field: 'charge_person',
      sortable: true,
    },{
      title: '负责人电话',
      field: 'tel',
    },{
      title: '设备运行状态',
      field: 'status',
      sortable: true,
      formatter: function(value){
        if(value == 3){
          return '开始'
        }else if(value == 4){
          return '停止'
        }else{
          return '暂停'
        }
      }
    },{
      title: '制冷状态',
      field: 'status_cold',
      sortable: true,
      formatter: function(value){
        return value == 1?'开':'关'
      }
    },{
      title: '制冷温度报警',
      field: 'alarm_cold',
      sortable: true,
      formatter: function(value){
        return value == 1?'开':'关'
      }
    },{
      title: '除氧状态',
      field: 'switch_o2',
      sortable: true,
      formatter: function(value){
        return value == 1?'开':'关'
      }
    },{
      title: '冷端温度（℃）',
      field: 'pt1',
      sortable: true,
      formatter: function(value){
        if(value != 0 && value != null && value != -999 && value != undefined){
          return value/10
        }
      }
    },{
      title: '热端温度（℃）',
      field: 'pt2',
      sortable: true,
      formatter: function(value){
        if(value != 0 && value != null && value != -999 && value != undefined){
          return value/10
        }
      }
    },{
      title: '含氧量（%）',
      field: 'pv',
      sortable: true,
      formatter: function(value){
        if(value != 0 && value != null && value != -999 && value != undefined){
          return value/10
        }
      }
    },{
      title: '搅拌电机',
      field: 'status_p4_jiaoban',
      sortable: true,
      formatter: function(value){
        return value == 1?'开':'关'
      }
    },{
      title: '一级错流泵',
      field: 'status_p4_beng1',
      sortable: true,
      formatter: function(value){
        return value == 1?'开':'关'
      }
    },{
      title: '二级错流泵',
      field: 'status_p4_beng2',
      sortable: true,
      formatter: function(value){
        return value == 1?'开':'关'
      }
    },{
      title: '除氧真空泵',
      field: 'status_p6_beng',
      sortable: true,
      formatter: function(value){
        return value == 1?'开':'关'
      }
    },{
      title: '经度',
      field: 'lng',
      visible: false
    },{
      title: '纬度',
      field: 'lat',
      visible: false
    },{
      title: '数据时间',
      field: 'insert_time',
      sortable: true,
      formatter: function(value){
        return formatTime(value)
      }
    }
  ]
})
$('#indextable').on('click-row.bs.table',function(e,row,element){
  $(element).addClass('bg').siblings().removeClass('bg');
  openPoint(row)
  tableRow = row
  $('#tabbar li').attr('myload','unload')
  tab(row)
})
function dataLatest(){
  $.ajax({
    type: "post",
    url: reqDomain + "/index/equipment/data_latest",
    dataType: "json",
    success: function (data) {
      if(data.code == 200){
        for(var i=0;i<data.result.length;i++){
          for(var key in data.result[i]){
            if(data.result[i][key] == '-999'){
              data.result[i][key] = '-'
            }
          }
        }
        $('#indextable').bootstrapTable('load', data.result);
      }
    }
  });
}
// 底部选项卡切换
$('#tabbar li').click(function(){
  var index = $(this).index();
  $(this).addClass('current').siblings().removeClass('current');
  $('#tabMain>li').eq(index).addClass('current').siblings().removeClass('current');
  tab(tableRow)
})
function tab(row){
  var index = $('#tabbar li.current').index();
  if($('#tabbar li').eq(index).attr('myload')=='unload' && tableRow!=null){
    $('.equipment').text('设备：'+row.name+'（'+row.box_id+'）');
    $('#tabbar li').eq(index).attr('myload','load');
    if(index == 0){
      boxInfo(row)
    }else if(index == 1){
      pivot()
    }else if(index == 2){
      curve()
    }
  }
}
// 设备信息
function boxInfo(row){
  $.ajax({
    type: "post",
    url: reqDomain + "/index/equipment/equipment_details",
    data: {
      id: row.id
    },
    dataType: "json",
    success: function (data) {
      if(data.code == 200){
        box_info = data.result;
        var str = '<li>设备名称：'+data.result.name+'</li>'+
                  '<li>设备id：'+data.result.box_id+'</li>'+
                  '<li>用户：'+data.result.project_name+'</li>'+
                  '<li>负责人：'+data.result.charge_person+'</li>'+
                  '<li>负责人电话：'+data.result.tel+'</li>'+
                  '<li>省份：'+data.result.province+'</li>'+
                  '<li>城市：'+data.result.city+'</li>'+
                  '<li>地址：'+data.result.address+'</li>'+
                  '<li>备注：'+data.result.remarks+'</li>'+
                  '<li>实施时间：'+formatTime(data.result.install_time)+'</li>'
        $('#box_info').html(str);
        var runTimeStr = '<li>搅拌机单词运行时长（分）：2</li>'+
                         '<li>一级错流泵运行时间（分）：'+data.result.param_config_t5+'</li>'+
                         '<li>二级错流泵运行时间（分）：'+data.result.param_config_t4+'</li>';
        $('#run_time').html(runTimeStr)
        var cfgStr = '<li>超级密码：'+data.result.super_password+'</li>'+
                      '<li>剩余次数：'+data.result.num+'</li>'+
                      '<li><button type="button" class="btn btn-default btn-xs" onclick="config()">配置</button></li>';
        $('#config').html(cfgStr)
      }
    }
  });
}
// 配置密码
function config(){
  if(tableRow == null){
    layer.msg('先选择一个项目！');
    return false;
  }
  layer.open({
    id: 1,
    type: 1,
    title: tableRow.name + '密码次数配置',
    content: "<div class='config'>"+
              "<label>超级密码：<input type='text' class='form-control' id='cfgPassword' value='"+box_info.super_password+"'/></label>"+
              "<label>剩余次数：<input type='text' class='form-control' id='cfgTimes' value='"+box_info.num+"'/></label>"+
            "</div>",
    btn: ['确认','取消'],
    yes: function(index, layero){
      layer.confirm('确定要配置此参数么？',function(){
        $.ajax({
          type: "post",
          url: reqDomain + "/index/equipment/set_super_password",
          data: {
            box_id: tableRow.box_id,
            super_password: $('#cfgPassword').val(),
            num: $('#cfgTimes').val()
          },
          dataType: "json",
          success: function (data) {
            if(data.code == 200){
              layer.msg('设置成功');
              boxInfo(tableRow);
              setTimeout(function(){
                layer.closeAll()
              }, 500);
            }
          }
        });
      })
    },
    no: function(index,layero){
      layer.close(index)
    }
  })
}
// 历史数据
function pivot(){
  $.ajax({
    type: "post",
    url: reqDomain + "/index/equipment/data_list",
    data: {
      boxId: tableRow.box_id,
      startTime: $('#startTime').val(),
      endTime: $('#endTime').val()
    },
    dataType: "json",
    success: function (data) {
      if(data.code == 200){
        $('#dataTable').bootstrapTable({
          showColumns: true,
          showExport: true,
          exportDataType: 'all',
          striped : true,           
          pageNumber : 1,
          pagination : true,
          toolbar: '#boxDataOp',
          sidePagination : 'client',
          pageSize : 10,
          columns: [
            {
              title: '设备id',
              field: 'box_id',
              formatter: function(value){
                return parseInt(value).toString(16)
              }
            },{
              title: '设备运行状态',
              field: 'status',
              sortable: true,
              formatter: function(value){
                if(value == 3){
                  return '开始'
                }else if(value == 4){
                  return '停止'
                }else{
                  return '暂停'
                }
              }
            },{
              title: '制冷状态',
              field: 'status_cold',
              sortable: true,
              formatter: function(value){
                return value == '1'?'开':'关'
              }
            },{
              title: '制冷温度报警',
              field: 'alarm_cold',
              sortable: true,
            },{
              title: '除氧状态',
              field: 'switch_o2',
              sortable: true,
              formatter: function(value){
                return value == '1'?'开':'关'
              }
            },{
              title: '冷端温度',
              field: 'pt1',
              sortable: true,
            },{
              title: '热端温度',
              field: 'pt2',
              sortable: true,
            },{
              title: '含氧量',
              field: 'pv',
              sortable: true,
            },{
              title: '搅拌电机',
              field: 'status_p4_jiaoban',
              sortable: true,
              formatter: function(value){
                return value == 1?'开':'关'
              }
            },{
              title: '一级错流泵',
              field: 'status_p4_beng1',
              sortable: true,
              formatter: function(value){
                return value == 1?'开':'关'
              }
            },{
              title: '二级错流泵',
              field: 'status_p4_beng2',
              sortable: true,
              formatter: function(value){
                return value == 1?'开':'关'
              }
            },{
              title: '除氧真空泵',
              field: 'status_p6_beng',
              sortable: true,
              formatter: function(value){
                return value == 1?'开':'关'
              }
            },{
              title: '数据时间',
              field: 'insert_time',
              sortable: true,
              formatter: function(value){
                return formatTime(value)
              }
            }
          ],
          onPostBody:function(){
            var header = $(".fixed-table-header table thead tr th");
            var body = $(".fixed-table-header table tbody tr td");
            var footer = $(".fixed-table-header table tr td");
              body.each(function(){
              header.width((this).width());
              footer.width((this).width());
            });
          }
        })
        for(var i=0;i<data.result.length;i++){
          for(var key in data.result[i]){
            if(data.result[i][key] == '-999'){
              data.result[i][key] = '-'
            }
          }
        }
        $('#dataTable').bootstrapTable('load',data.result)
      }
    }
  });
}
// 数据曲线
function curve(){
  $.ajax({
    type: "post",
    url: reqDomain + "/index/equipment/data_list",
    data: {
      boxId: tableRow.box_id,
      startTime: $('#startTime1').val(),
      endTime: $('#endTime1').val()
    },
    dataType: "json",
    success: function (data) {
      if(data.code == 200){
        for(var i=0;i<data.result.length;i++){
          for(var key in data.result[i]){
            if(data.result[i][key] == '-999'){
              data.result[i][key] = '-'
            }
          }
        }
        var chart = echarts.init(document.getElementById('chart'));
        var datatime = [];
        var status = [];
        var status_cold = [];
        var alarm_cold = [];
        var switch_o2 = [];
        var pt1 = [];
        var pt2 = [];
        var pv = [];
        var status_p4_jiaoban = [];
        var status_p4_beng1 = [];
        var status_p4_beng2 = [];
        var status_p6_beng = [];
        for(var i=0;i<data.result.length;i++){
          datatime[i] = formatTime(parseInt(data.result[i].insert_time));
          status[i] = data.result[i].status;
          status_cold[i] = data.result[i].status_cold;
          alarm_cold[i] = data.result[i].alarm_cold;
          switch_o2[i] = data.result[i].switch_o2;
          pt1[i] = data.result[i].pt1;
          pt2[i] = data.result[i].pt2;
          pv[i] = data.result[i].pv;
          status_p4_jiaoban[i] = data.result[i].status_p4_jiaoban;
          status_p4_beng1[i] = data.result[i].status_p4_beng1;
          status_p4_beng2[i] = data.result[i].status_p4_beng2;
          status_p6_beng[i] = data.result[i].status_p6_beng;
        }
        option = {
					tooltip: {
						trigger: 'axis'
					},
					legend: {
						data: ['设备运行状态', '制冷状态', '制冷温度报警', '除氧状态', '冷端温度','	热端温度','含氧量','搅拌电机','一级错流泵','二级错流泵','除氧真空泵'],
						right: 30,
						top: 10,
					},
					grid: {
						left: '2%',
						right: '3%',
						bottom: '2%',
						containLabel: true
					},
					xAxis: {
						type: 'category',
						axisLine: {
							onZero: false
						},
						axisLabel: {
							textStyle: {
								color: '#000000',
								fontSize: '10'
							},
						},
						axisTick: {
							show: false,
						},
						boundaryGap: false,
						splitLine: {
							show: true,
							lineStyle: {
								color: ['#EEEEEE']
							}
						},
						data: datatime.reverse()
					},
					yAxis: {
						type: 'value',
						axisLabel: {
							formatter: '{value}',
							textStyle: {
								color: '#3BA1D6',
								fontSize: '10'
							},
						},
						axisTick: {
							show: false,
						},
						splitLine: {
							show: true,
							lineStyle: {
								color: ['#EEEEEE']
							}
						},
          },
          toolbox: {
            feature: {
              saveAsImage: {}//保存成图片
            }
          },
					dataZoom:[
						{
							type:'inside',
							start:0,
							end:100
						},
						{
							show:true,
							type:'slider',
							y:'90%',
							start:50,
							end:100
						}
					],
					series: [{
							name: '设备运行状态',
							type: 'line',
							smooth: true,
							lineStyle: {
								normal: {
									width: 3,
								}
							},
							data: status
						},
						{
							name: '制冷状态',
							type: 'line',
							smooth: true,
							lineStyle: {
								normal: {
									width: 3,
								}
							},
							data: status_cold
						},
						{
							name: '制冷温度报警',
							type: 'line',
							smooth: true,
							lineStyle: {
								normal: {
									width: 3,
								}
							},
							data: alarm_cold
						},
						{
							name: '除氧状态',
							type: 'line',
							smooth: true,
							lineStyle: {
								normal: {
									width: 3,
								}
							},
							data: switch_o2
						},
						{
							name: '冷端温度',
							type: 'line',
							smooth: true,
							lineStyle: {
								normal: {
									width: 3,
								}
							},
							data: pt1
            },
            {
							name: '热端温度',
							type: 'line',
							smooth: true,
							lineStyle: {
								normal: {
									width: 3,
								}
							},
							data: pt2
            },
            {
							name: '含氧量',
							type: 'line',
							smooth: true,
							lineStyle: {
								normal: {
									width: 3,
								}
							},
							data: pv
            },
            {
							name: '子流程4-搅拌电机-状态',
							type: 'line',
							smooth: true,
							lineStyle: {
								normal: {
									width: 3,
								}
							},
							data: status_p4_jiaoban
            },
            {
							name: '子流程4-一级错流泵-状态',
							type: 'line',
							smooth: true,
							lineStyle: {
								normal: {
									width: 3,
								}
							},
							data: status_p4_beng1
            },
            {
							name: '子流程4-二级错流泵-状态',
							type: 'line',
							smooth: true,
							lineStyle: {
								normal: {
									width: 3,
								}
							},
							data: status_p4_beng2
            },
            {
							name: '子流程6-除氧真空泵-状态',
							type: 'line',
							smooth: true,
							lineStyle: {
								normal: {
									width: 3,
								}
							},
							data: status_p6_beng
						},
					]
				};
				chart.setOption(option);
				window.addEventListener('resize',function(){
					chart.resize();
				})
      }
    }
  });
}