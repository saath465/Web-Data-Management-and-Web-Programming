// Put your zillow.com API key here
var zwsid = "X1-ZWz1g1l9zoyoln_8469v";

var request = new XMLHttpRequest();

//global variables
var lati;
var longi;
var value;
var mark_arr = [];
var map;
var geocoder;
var lat_cl;
var lon_cl;
var iwin;
var n_addr;
var state_zip;
var a, c, s, z;
var z_val;

//function to initialize the web page when loaded
function initialize () {
  //initialize_map();
  //obtained from Google API Information page
  geocoder = new google.maps.Geocoder();
  iwin = new google.maps.InfoWindow;
  map = new google.maps.Map(document.getElementById("gmap"),{       //obtained from Google API Information page
    center: {lat: 32.75, lng:-97.13},
    zoom:17
  });

  //adding the event for the click on map action
  //obtained from Google API Information page
  google.maps.event.addListener(map, 'click', function(event){
    lat_cl = event.latLng.lat();
    lon_cl = event.latLng.lng();
    getAdd_latlng(geocoder, map, iwin);
  });
}

//function to display Results when response comes from zillow
function displayResult () {
    if (request.readyState == 4) {
        var xml = request.responseXML.documentElement;
        //get_addr(xml);
        value = xml.getElementsByTagName("zestimate")[0].getElementsByTagName("amount")[0].innerHTML;
        if(value == ""){
          alert("Zillow Doesnt Show results for Apartments");
          exit(0);
        }
        print_result(a, c, s, z, value);
        MarkCo_Ord(lati,longi,value);
    }
}

//function to send request to zillow when user enters the input
function sendRequest () {
    map_clear();
    var address = document.getElementById("address").value;
    get_addr(address);
    /*var city = document.getElementById("city").value;
    var state = document.getElementById("state").value;
    var zipcode = document.getElementById("zipcode").value;*/
    request.onreadystatechange = displayResult;
    request.open("GET","proxy.php?zws-id="+zwsid+"&address="+a+"&citystatezip="+c+"+"+s+"+"+z);
    request.withCredentials = "true";
    request.send(null);
}

//function to call the geocoding api of google and get the co ordinates to mark the
//address on the map.
function MarkCo_Ord(lat,long,val){
    var ad = a;
    var city = c;
    var state = s;
    var zip = z;
    var String = ad + "," + city + "," + state + "," + zip;
    //call the geocod function of the google maps API to get the marker latitude
    // and longitude

    //obtained from Google API Information page
    geocoder.geocode({'address' : String}, function(res, stat){     //pass the address to the google API to obtain the latitude and
      //longitude.
      if(stat == "OK"){
        map.setCenter(res[0].geometry.location);
        var mark = new google.maps.Marker({                         //place the marker at location
        position : res[0].geometry.location,
        map : map
      });
      mark_arr.push(mark);
      iwin.setContent(res[0].formatted_address + " $" + val);     //print the address and value in the map
      iwin.open(map,mark);
    }
    else{
      alert("Google Maps API returned status:" + stat);
    }
  });
}

//Function to obtain the address from the input string supplied from the user
function get_addr(add){
  var add_arr = add.split(",",3);
  var s_z = add_arr[2].split(" ", 3);
  a = add_arr[0]; c = add_arr[1]; s = s_z[0]; z = s_z[1];
}

//function to clear the map Marker
//obtained from Google API Information page
function map_clear(){
  for(var i = 0; i < mark_arr.length; i++){
    mark_arr[i].setMap(null);
  }
  mark_arr.length = 0;
}

//function to obtained the address of the house selected when the user clicks on the map
//obtained from Google API Information page
function getAdd_latlng(geocoder, map, iwin){
  map_clear();
  var latlng = {lat: lat_cl,lng: lon_cl};                                 //assign the on click latitude and logitude
  geocoder.geocode({'location': latlng}, function(res, stat){             //send lat and longitude to google API to obtain the address
    if(stat == "OK"){
      var new_add = res[0].formatted_address;
      sendreq_new(new_add);                                               //call function to send request to Zillow.
      if(res[0]){
        map.setZoom(17);
        //obtained from Google API Information page
        var mark = new google.maps.Marker({                               //Set marker to the clicked position
        position : latlng,
        map : map
      });
        mark_arr.push(mark);
        iwin.setContent(res[0].formatted_address);                        //print the address
        iwin.open(map,mark);
        /*var new_add = res[0].formatted_address;
        sendreq_new(new_add)*/;
      }
      else{
        alert("Noting returned from google API");
      }
    }else{
      alert("Google Maps API returned status:" + stat);
    }
  });
}

//function to send request to the Zillow API along with address retured from google API
function sendreq_new(addr){
  n_addr = addr.split(",", 3);
  state_zip = n_addr[2].split(" ", 3);
  request.onreadystatechange = displayResult_new;
  request.open("GET","proxy.php?zws-id="+zwsid+"&address=" + n_addr[0] + "&citystatezip=" + n_addr[1] + "+" + state_zip[0] + "+" + state_zip[1]);
  request.withCredentials = "true";
  request.send(null);
  return;
}

//function to retrieve the value of the house from the zillow reply/response.
function displayResult_new(){
  if(request.readyState == 4){
    var ret = request.responseXML.documentElement;
    var z_val = ret.getElementsByTagName("zestimate")[0].getElementsByTagName("amount")[0].innerHTML;
    print_result(n_addr[0], n_addr[1], state_zip[1], state_zip[2], z_val);
    return;
  }
}

//function to print the values of the result in the text box
function print_result(addr, city, state, zip, val){
  document.getElementById("output").innerHTML += "Address: " + addr + "<br/>City: " +
    city + "<br/>State: " + state + "<br/>ZipCode: " + zip + "<br/>Value in $: " +
      val + "<br/><br/><br/>";
      return;
}

//function to clear the text input form.
function clearForm(){
  document.getElementById("address").value = "";
}
