// :: Chart ::
// :: @cendekiapp ::

	//Stackarea Chart
	function stackAreaChart(divID, category, data, marginBot,is_web){
		var chart;
		chart = new Highcharts.Chart({
			chart: {
				renderTo: divID,
				type: 'area',
				zoomType: 'x',
				marginTop: 50,
				backgroundColor: null,
				marginBottom: marginBot
			},
			title: false,
			subtitle: false,
			xAxis: {
				gridLineWidth: 1,
				lineColor: '#000',
				tickColor: '#000',
				enabled: false,
				categories: category,
				tickmarkPlacement: 'on',
				title: {
					enabled: false
				},
				labels: {
				rotation: -45,
				type: 'datetime',
				align: 'right',
				min:0,
				style: {
					font: 'normal 9px Verdana, sans-serif'
				},
				formatter: function() {
					var month = this.value.substr(5,2);
					var tgl = this.value.substr(8,2);
					return tgl+'/'+month;
				}
			}
			},
			yAxis: {
				minorTickInterval: 'auto',
				  lineColor: '#000',
				  lineWidth: 1,
				  tickWidth: 1,
				  tickColor: '#000',
				title: false,
				plotLines: [{
					value: 0,
					width: 1,
					color: '#808080'
				}],
				labels: {
					formatter: function() {
						return Highcharts.numberFormat(this.value, 0, ',');
					}
				}
			},
			tooltip: {
				formatter: function() {
					return ''+
						this.x +': '+ Highcharts.numberFormat(this.y, 0, ',');
				}
			},
			plotOptions: {
				area: {
					stacking: 'normal',
					lineColor: '#666666',
					lineWidth: 1,
					marker: {
						lineWidth: 1,
						lineColor: '#666666'
					},
					events: {
						click: function(event) {
							var keyID = event.point.series.name;
							var dt = event.point.category;
							if(is_web){
								siteconversationByDateGlobal(dt+'_'+keyID, 0);
							}else{
								conversationByDateGlobal(dt+'_'+keyID, 0);
							}
						}
					}
				}
			},
			credits: false,
			legend: {
				align: 'right',
				verticalAlign: 'top',
				floating: true,
				x: -10,
				y: 0,
				itemStyle: {
					fontSize: '11px'
				},
				borderWidth: 0,
				labelFormatter: function() {
					return '<span style="color: '+this.color+';">'+ this.name + '</span>';
				}
			},
			series: data
		});
	}
	
	//Horizontal Bar Chart
	function horizontalBarChart(divID, category, data){
		var chart;
		chart = new Highcharts.Chart({
			chart: {
				renderTo: divID,
				type: 'bar',
				zoomType: 'x',
				marginTop: 10,
				marginBottom: 20
			},
			title: false,
			subtitle: false,
			xAxis: {
				gridLineWidth: 1,
				lineColor: '#000',
				tickColor: '#000',
				enabled: false,
				categories: category,
				tickmarkPlacement: 'on',
				title: {
					enabled: false
				},
				labels: {
				align: 'right',
				style: {
					font: 'normal 12px Verdana, sans-serif'
				}
			}
			},
			yAxis: {
				minorTickInterval: 'auto',
				  lineColor: '#000',
				  lineWidth: 1,
				  tickWidth: 1,
				  tickColor: '#000',
				title: {
					text: ''
				},
				plotLines: [{
					value: 0,
					width: 1,
					color: '#808080'
				}],
				title: false,
				labels: {
					formatter: function() {
						return Highcharts.numberFormat(this.value, 0, ',');
					}
				}
			},
			tooltip: {
				formatter: function() {
					return ''+
						this.x +': '+ Highcharts.numberFormat(this.y, 0, ',');
				}
			},
			plotOptions: {
				area: {
					stacking: 'normal',
					lineColor: '#666666',
					lineWidth: 1,
					marker: {
						lineWidth: 1,
						lineColor: '#666666'
					}
				}
			},
			credits: false,
			// legend: {
				// align: 'right',
				// verticalAlign: 'top',
				// floating: true,
				// x: -10,
				// y: 0,
				// borderWidth: 0,
				// labelFormatter: function() {
					// return '<span style="color: '+this.color+';">'+ this.name + '</span>';
				// }
			// },
			legend: false,
			series: [data]
		});
	}
	
	//Pie Chart
	function pieChart(divID, data){
		var chart;
		var _color = ['#008000','#FF0000','#CCCCCC'];
		chart = new Highcharts.Chart({
			chart: {
				renderTo: divID,
				backgroundColor: null,
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false
			},
			title: false,
			tooltip: {
				formatter: function() {
					return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.y,0);
				}
			},
			credits: false,
			legend: {
				align: 'center',
				verticalAlign: 'bottom',
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					showInLegend: true,
					shadow: false,
					// size: 210,
					dataLabels: {
						enabled: true,
						distance: -30,
						color: '#ffffff',
						// connectorColor: (Highcharts.theme && Highcharts.theme.textColor),
						formatter: function() {
							return Highcharts.numberFormat(this.percentage,2) +' %';
						}
					}
				}
			},
			series: [{
				type: 'pie',
				name: 'pie chart',
				data: data
			}]
		});
	}
	
	//Mini Pie Chart - Channel
	function pieChartMiniChannel(divID, data, pieSize){
		var chart;
		chart = new Highcharts.Chart({
			chart: {
				renderTo: divID,
				backgroundColor: null,
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false
			},
			title: false,
			tooltip: {
				formatter: function() {
					return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.y,0);
				}
			},
			credits: false,
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					showInLegend: true,
					size: pieSize,
					dataLabels: {
						enabled: true,
						distance: -20,
						color: '#FFFFFF',
						// connectorColor: (Highcharts.theme && Highcharts.theme.textColor),
						formatter: function() {
							if (Highcharts.numberFormat(this.percentage,2) != 0.00){
								if(Highcharts.numberFormat(this.percentage,2) == 100.00){
									return Highcharts.numberFormat(this.percentage,0) +' %';
								}else{
									return Highcharts.numberFormat(this.percentage,2) +' %';
								}
							}
						}
					}
				}
			},
			legend: false,
			series: [{
				type: 'pie',
				name: 'pie chart',
				data: data
			}]
		});
	}
	
	//Mini Pie Chart - Sentiment
	function pieChartMiniSentiment(divID, data, pieSize){
		var chart;
		chart = new Highcharts.Chart({
			chart: {
				renderTo: divID,
				backgroundColor: null,
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false
			},
			title: false,
			tooltip: {
				formatter: function() {
					if (this.y != 0){
						return '<b>'+ this.point.name +'</b>: '+Highcharts.numberFormat(this.y,0);
					}
				}
			},
			credits: false,
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					showInLegend: true,
					size: pieSize,
					dataLabels: {
						enabled: true,
						distance: -20,
						color: '#FFFFFF',
						// connectorColor: (Highcharts.theme && Highcharts.theme.textColor),
						formatter: function() {
							if (this.y != 0){
								switch(this.point.name){
									case 'Positive':
										return '+';
										break;
									case 'Negative':
										return '-';
										break;
									default:
										return 'N';
								}
							}
						}
					}
				}
			},
			legend: false,
			series: [{
				type: 'pie',
				name: 'pie chart',
				data: data
			}]
		});
	}
	
	//Mini Line Chart
	function lineChartMini(divID, category, data, legend){
		var chart;
		if (legend == true){
			var legendz = {
				align: 'right',
				verticalAlign: 'top',
				backgroundColor: '#FFFFFF',
				floating: true,
				borderWidth: 0,
				labelFormatter: function() {
					return '<span style="color: '+this.color+';">'+ this.name + '</span>';
				}
			};
			
			var mrTop = 50;
		}else{
			var legendz = false;
			var mrTop = 10;
		}
		chart = new Highcharts.Chart({
			chart: {
				renderTo: divID,
				type: 'line',
				zoomType: 'x',
				marginTop: mrTop,
				backgroundColor: null,
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false
			},
			title: false,
			subtitle: false,
			xAxis: {
				gridLineWidth: 1,
				lineColor: '#000',
				tickColor: '#000',
				enabled: false,
				categories: category,
				tickmarkPlacement: 'on',
				title: {
					enabled: false
				},
				labels: {
				rotation: -45,
				align: 'right',
				style: {
					font: 'normal 9px Verdana, sans-serif'
					}
				}
			},
			yAxis: {
				minorTickInterval: 'auto',
				  lineColor: '#000',
				  lineWidth: 1,
				  tickWidth: 1,
				  tickColor: '#000',
				title: {
					text: ''
				},
				plotLines: [{
					value: 0,
					width: 1,
					color: '#808080'
				}],
				title: false,
				labels: {
					formatter: function() {
						return Highcharts.numberFormat(this.value, 0, ',');
					}
				}
			},
			tooltip: {
				formatter: function() {
					return ''+
						this.x +': '+ Highcharts.numberFormat(this.y, 0, ',');
				}
			},
			plotOptions: {
				area: {
					stacking: 'normal',
					lineColor: '#666666',
					lineWidth: 1,
					marker: {
						lineWidth: 1,
						lineColor: '#666666'
					}
				}
			},
			credits: false,
			legend: legendz,
			series: data
		});
	}
	
	//Line Chart
	//Line Chart
  function lineChart(divID, category, data, type){
    if (type == true){
      var marginT = 80;
      var legendY = 10;
    }else{
      var marginT = 60;
      var legendY = -10;
    }
    var chart;
    chart = new Highcharts.Chart({
      chart: {
        renderTo: divID,
        type: 'line',
        zoomType: 'x',
        marginTop: marginT,
        marginBottom: 50
      },
      title: false,
      subtitle: false,
      xAxis: {
        gridLineWidth: 1,
        lineColor: '#000',
        tickColor: '#000',
        enabled: false,
        categories: category,
        tickmarkPlacement: 'on',
        title: {
          enabled: false
        },
        labels: {
        rotation: -45,
        align: 'right',
        style: {
          font: 'normal 9px Verdana, sans-serif'
        }
      }
      },
      yAxis: {
        minorTickInterval: 'auto',
          lineColor: '#000',
          lineWidth: 1,
          tickWidth: 1,
          tickColor: '#000',
        title: {
          text: ''
        },
        plotLines: [{
          value: 0,
          width: 1,
          color: '#808080'
        }],
        title: false,
        labels: {
          formatter: function() {
            return Highcharts.numberFormat(this.value, 0, ',');
          }
        }
      },
      tooltip: {
        formatter: function() {
          return ''+
            this.x +': '+ Highcharts.numberFormat(this.y, 0, ',');
        }
      },
      plotOptions: {
        area: {
          stacking: 'normal',
          lineColor: '#666666',
          lineWidth: 1,
          marker: {
            lineWidth: 1,
            lineColor: '#666666'
          }
        }
      },
      credits: false,
      legend: {
        // align: 'right',
        verticalAlign: 'top',
        floating: true,
        // x: legendT,
        y: legendY,
        // borderWidth: 0,
        labelFormatter: function() {
          return '<span style="color: '+this.color+';">'+ this.name + '</span>';
        }
      },
            series: data
    });
  }
  //linechart where data splitted by rule id
	function lineChartByRuleId(divID, category, data, type, ruleID){
		if (type == true){
			var marginT = 80;
			var legendY = 10;
		}else{
			var marginT = 60;
			var legendY = -10;
		}
		var chart;
		chart = new Highcharts.Chart({
			chart: {
				renderTo: divID,
				type: 'line',
				zoomType: 'x',
				marginTop: marginT,
				marginBottom: 50
			},
			title: false,
			subtitle: false,
			xAxis: {
				gridLineWidth: 1,
				lineColor: '#000',
				tickColor: '#000',
				enabled: false,
				categories: category,
				tickmarkPlacement: 'on',
				title: {
					enabled: false
				},
				labels: {
				rotation: -45,
				align: 'right',
				style: {
					font: 'normal 9px Verdana, sans-serif'
				},
				formatter: function() {
						var month = this.value.substr(5,2);
						var tgl = this.value.substr(8,2);
						return tgl+'/'+month;
					}
			}
			},
			yAxis: {
				minorTickInterval: 'auto',
				  lineColor: '#000',
				  lineWidth: 1,
				  tickWidth: 1,
				  tickColor: '#000',
				title: {
					text: ''
				},
				plotLines: [{
					value: 0,
					width: 1,
					color: '#808080'
				}],
				title: false,
				labels: {
					formatter: function() {
						return Highcharts.numberFormat(this.value, 0, ',');
					}
				}
			},
			tooltip: {
				formatter: function() {
					return ''+
						this.x +': '+ Highcharts.numberFormat(this.y, 0, ',');
				}
			},
			plotOptions: {
				series: {
					stacking: 'normal',
					lineColor: '#666666',
					lineWidth: 1,
					marker: {
						lineWidth: 1,
						lineColor: '#666666'
					},
					events: {
						click: function(event) {
							var keyID = event.point.series.name;
							var dt = event.point.category;
							conversationByDate(dt+'_'+keyID, 0);
						}
					}
				}
			},
			credits: false,
			legend: {
				// align: 'right',
				verticalAlign: 'top',
				floating: true,
				// x: legendT,
				y: legendY,
				// borderWidth: 0,
				labelFormatter: function() {
					var nameL;
					var keywordID = this.name;
					$.each(ruleID, function(k,v){
						if(v.id == keywordID){
							
							nameL = v.keys;
						}
					});
					
					return '<span style="color: '+this.color+';">'+nameL+ '</span>';
				}
			},
            series: data
		});
	}
	
	function negativeBarChart(divID, category, data){
		var chart;
		chart = new Highcharts.Chart({
			chart: {
				renderTo: divID,
				type: 'column'
			},
			credits: {
				enabled: false
			},
			title: {
				text: false,
			},
			xAxis: {
				gridLineWidth: 1,
				lineColor: '#000',
				tickColor: '#000',
				enabled: false,
				categories: category,
				tickmarkPlacement: 'on',
				title: {
					enabled: false
				},
				labels: {
					rotation: -45,
					align: 'right',
					style: {
						font: 'normal 9px Verdana, sans-serif'
					},
					formatter: function() {
						var month = this.value.substr(5,2);
						var tgl = this.value.substr(8,2);
						return tgl+'/'+month;
					}
				}
			},
			yAxis: {
				minorTickInterval: 'auto',
				  lineColor: '#000',
				  lineWidth: 1,
				  tickWidth: 1,
				  tickColor: '#000',
				title: {
					text: ''
				},
				plotLines: [{
					value: 0,
					width: 1,
					color: '#808080'
				}]
			},
			tooltip: {
				 formatter: function() {
					return ''+
					   this.series.name +': '+ Highcharts.numberFormat(this.y, 0);
				 }
			},
			legend: {
				enabled: true
			},
			plotOptions: {
				series: {
					cursor: 'pointer',
					events: {
						click: function(event) {
							var nama = event.point.series.name;
							var sentType;
							var dt = event.point.category;
							if(nama == 'Favourable'){
								sentType = '1';
							}else if(nama == 'Unfavourable'){
								sentType = '2';
							}
							popupSentiment(sentType+'_'+dt, 0);
						}
					}
				}
			  },
			series: data
		});
	}
	
	function smac_number2(str){
		var n = parseFloat(str);
		var s = "";
		if(n>=1000000){
			s = Math.floor(n/1000000)+"M+";
		}else if(n>=1000){
			s = number_format(n);
		}else{
			s = n;
		}
		return s;
	}
	function roundNumber(num, dec) {
		
		var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
		return result;
	}
	
	//Wordcloud
	function wordcloud(wordData,addWorkflowLink, index){
		var workflow_link = (typeof addWorkflowLink==='undefined') ? 0 : addWorkflowLink;
		var index = (typeof index==='undefined') ? 0 : index;
		
		try{
			var temp = new Array();
			$.each(wordData, function(k, v) {
				temp.push(parseInt(v.total));
			});
			
				//Find Max value of Array
				var maxValue=temp[0];
				var i=0;
				while(i<temp.length){
					maxValue=Math.max(maxValue,temp[i]);
					i++;
				}
				
				//Convert simple value comparison
				var simpleVal = new Array();
				$.each(temp, function(k, v){
					var val = (v/maxValue)*10;
					simpleVal.push(val);
				});

			var str="";
			$.each(wordData, function(k, v) {
				var fontSize = ((Math.ceil(simpleVal[k])/10)*30)+6;
				var zIndex = 50 - fontSize;
				var sentiment;
				if(v.sentiment>0){
					 sentiment = '#8ec448';
				}else if(v.sentiment<0){
					sentiment = '#ff0000';
				}else{
					sentiment = '#666666';
				}
				if(workflow_link==1){
					/*
					 * <a href="#/workflow/'+v.keyword+'/1">Twitter</a>
					 * <a href="#/workflow/'+v.keyword+'/2">Facebook</a>
					 * <a href="#/workflow_gcs/'+v.keyword+'/1">Blog</a>
					 * <a href="#/workflow_gcs/'+v.keyword+'/2">Forum</a>
					 * ....
					 */
					str+='<span><a href="#/select_channel/'+index+'/'+v.keyword+'" style="font-size:'+fontSize+'px; color:'+sentiment+';position:relative;z-index:'+zIndex+';">'+v.keyword+'</a>  </span>';
				}else{
					str+='<span><a href="#" style="font-size:'+fontSize+'px; color:'+sentiment+';position:relative;z-index:'+zIndex+';">'+v.keyword+'</a>  </span>';
				}
			});
				str+=wordcloudChannel(index);
			return str;
		}catch(e){
			
			return "Wordcloud is not available yet.";
		}
	}
	
	function date_dmySlash_to_ymdDash(tgl){
		var tempDay = tgl.substr(0,2);
		var tempMonth = tgl.substr(3,2);
		var tempYear = tgl.substr(6,4);
		
		return tempYear+'-'+tempMonth+'-'+tempDay;
	}
	
	function smacLoader(divID, size, loaderText){
		$("#"+divID+"").html("<div style='text-align: center;'><span style='color:black;display:block;margin-bottom: 10px;'>Loading "+loaderText+"</span><img src='images/"+size+".gif'/></div>");						
	}
	
	//Pagination
	function smacPagination(data, n, divPage, type, fungsi, setPerPage){
		if(divPage == 'responsePaging'){
			$("body,html").scrollTop(600);
		}else{
			$("body,html").scrollTop(0);
		}
		$('#'+divPage).html('');
		var perPage = 10;
		if(setPerPage > perPage || setPerPage < perPage){perPage = setPerPage;}
		var totalPage = Math.ceil(parseInt(data)/perPage);
		if (totalPage > 1){
			//Current Page
			var backward = 0;
			var current = totalPage-(totalPage-n);
			$("#"+divPage+" a.cPaging").removeClass('active');
		
			if(current>=3){
				var lastPage = totalPage-current;
				
				if(lastPage<3){
					
					var str="";
					var i;
					if(totalPage <= 4){
						var nx = totalPage-1;
					}else{
						var nx = 4;
					}
					var p = totalPage-nx;
					
					if(totalPage < 5){
						var maxPageShow = totalPage;
					}else{
						var maxPageShow = 5;
					}
					
					//prev btn
					if(current != 1){
						var realPage = ((current-1)*perPage)-perPage;
						str+='<a class="cPaging prev" href="#p'+(current-1)+'" onClick="'+fungsi+'(\''+type+'\', '+realPage+');smacPagination('+data+','+(current-1)+',\''+divPage+'\',\''+type+'\',\''+fungsi+'\','+setPerPage+');return false;">&laquo;</a>';
					}
					//paging number
					for(i=0;i<maxPageShow;i++){
						if(p == 1){var realPage = 0;}else{var realPage = ((p-1)*perPage);}
						str+='<a id="p'+p+'" class="cPaging" href="#p'+p+'" onClick="'+fungsi+'(\''+type+'\', '+realPage+');smacPagination('+data+','+p+',\''+divPage+'\',\''+type+'\',\''+fungsi+'\','+setPerPage+');return false;">'+p+'</a>';
						p++;
					}
					//next btn
					if(current != totalPage){
						if(current == 1){var realPage = perPage;}else{var realPage = (current*perPage);}
						str+='<a class="cPaging next" href="#p'+(current+1)+'" onClick="'+fungsi+'(\''+type+'\', '+realPage+');smacPagination('+data+','+(current+1)+',\''+divPage+'\',\''+type+'\',\''+fungsi+'\','+setPerPage+');return false;">&raquo;</a>';
					}
					$("#"+divPage+"").html(str);
					
					//highlight
					$("#"+divPage+" a#p"+current+"").addClass('active');
				}else{
					
					pageLog = 1;
					var str="";
					var i;
					var p = current-2;
					
					if(totalPage < 5){
						var maxPageShow = totalPage;
					}else{
						var maxPageShow = 5;
					}
					
					//prev btn
					if(current != 1){
						var realPage = ((current-1)*perPage)-perPage;
						str+='<a class="cPaging prev" href="#p'+(current-1)+'" onClick="'+fungsi+'(\''+type+'\', '+realPage+');smacPagination('+data+','+(current-1)+',\''+divPage+'\',\''+type+'\',\''+fungsi+'\','+setPerPage+');return false;">&laquo;</a>';
					}
					//paging number
					for(i=0;i<maxPageShow;i++){
						if(p == 1){var realPage = 0;}else{var realPage = ((p-1)*perPage);}
						str+='<a id="p'+p+'" class="cPaging" href="#p'+p+'" onClick="'+fungsi+'(\''+type+'\', '+realPage+');smacPagination('+data+','+p+',\''+divPage+'\',\''+type+'\',\''+fungsi+'\','+setPerPage+');return false;">'+p+'</a>';
						p++;
					}
					//next btn
					if(current != totalPage){
						if(current == 1){var realPage = perPage;}else{var realPage = (current*perPage);}
						str+='<a class="cPaging next" href="#p'+(current+1)+'" onClick="'+fungsi+'(\''+type+'\', '+realPage+');smacPagination('+data+','+(current+1)+',\''+divPage+'\',\''+type+'\',\''+fungsi+'\','+setPerPage+');return false;">&raquo;</a>';
					}
					$("#"+divPage+"").html(str);
					
					//highlight
					$("#"+divPage+" a#p"+current+"").addClass('active');
				}
			}else{		
					var str="";
					var i;
					var p = 1;
					
					if(totalPage < 5){
						var maxPageShow = totalPage;
					}else{
						var maxPageShow = 5;
					}
					
					//prev btn
					if(current != 1){
						var realPage = ((current-1)*perPage)-perPage;
						str+='<a class="cPaging prev" href="#p'+(current-1)+'" onClick="'+fungsi+'(\''+type+'\', '+realPage+');smacPagination('+data+','+(current-1)+',\''+divPage+'\',\''+type+'\',\''+fungsi+'\','+setPerPage+');return false;">&laquo;</a>';
					}
					//paging number
					for(i=0;i<maxPageShow;i++){
						if(p == 1){var realPage = 0;}else{var realPage = ((p-1)*perPage);}
						str+='<a id="p'+p+'" class="cPaging" href="#p'+p+'" onClick="'+fungsi+'(\''+type+'\', '+realPage+');smacPagination('+data+','+p+',\''+divPage+'\',\''+type+'\',\''+fungsi+'\','+setPerPage+');return false;">'+p+'</a>';
						p++;
					}
					//next btn
					if(current != totalPage){
						if(current == 1){var realPage = perPage;}else{var realPage = (current*perPage);}
						str+='<a class="cPaging next" href="#p'+(current+1)+'" onClick="'+fungsi+'(\''+type+'\', '+realPage+');smacPagination('+data+','+(current+1)+',\''+divPage+'\',\''+type+'\',\''+fungsi+'\','+setPerPage+');return false;">&raquo;</a>';
					}
					$("#"+divPage+"").html(str);
				
				//highlight
				$("#"+divPage+" a#p"+current+"").addClass('active');
			}
		}
	}