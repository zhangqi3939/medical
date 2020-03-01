var map;
var markClusterer;
initMap();
function initMap(){
  map = null;
  map = new BMap.Map('mapContent');
  point = new BMap.Point(101.404,35.917);
  map.centerAndZoom(point, 5);
  map.setMinZoom(0);
  map.enableScrollWheelZoom();
  var top_left_navigation = new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_LEFT}); 
		map.addControl(top_left_navigation);
		//清除当前地图内容
		map.clearOverlays();
		window.map = map;
}
function openPoint(row){
  var lng = row.lng;
  var lat = row.lat;
  point = new BMap.Point(lng,lat);
  map.centerAndZoom(point, 5);
  map.clearOverlays();
  var pt = new BMap.Point(lng, lat);
  var myIcon = new BMap.Icon("images/addr.png", new BMap.Size(19,50));
  var marker2 = new BMap.Marker(pt,{icon:myIcon});
  map.addOverlay(marker2);          
}