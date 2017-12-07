/* Associated music.js javascript file for music.html to retrieve the records of the Artist/Band name entered from
  Last.FM API, using AJAX and Rest API.
  The functions included are: display Artist Info, Display Top Albums of the Artist & display Similar Artists*/
// Put your Last.fm API key here
var api_key = "8980bddd9eb062dd03cba66654aee46a";

/* function to send request to get the basic information of the artist and the Bio*/
function sendRequest () {
    var xhr = new XMLHttpRequest();
    var method = "artist.getinfo";      //method passed to the API`
    var artist = encodeURI(document.getElementById("form-input").value);
    xhr.open("GET", "proxy.php?method="+method+"&artist="+artist+"&api_key="+api_key+"&format=json", true);
    xhr.setRequestHeader("Accept","application/json");
    xhr.onreadystatechange = function () {
        if (this.readyState == 4) {
            var dec_art = JSON.parse(this.responseText);      //parse the reseponse from the server
            var dec_str_art = JSON.stringify(dec_art,undefined,2);
            var dec_info = dec_art.artist;        //parsed information in Javascript object format
            //document.getElementById("output").innerHTML=  "<pre>"+ str +"</pre>";
            dataRetr(dec_info,artist);        //the parsed Javascript object is passed to the function
        }
    };
    xhr.send(null);
}

//Function to display the artist information, image, URl to the webpage and the bio of the artist
function dataRetr(data, name_artist){
  document.getElementById("output").style.fontWeight = 700;       //set the style of the text
  document.getElementById("output").innerHTML = data.name;        //display name
  var arlink = "Link to Artists Last.FM webpage";
  var res_link = arlink.link(data.url);
  document.getElementById('artist_image').innerHTML = res_link;       //display the link
  document.getElementById("artist_ima").src = data.image[2]["#text"];       //display the image [by default size is Large]
  document.getElementById("art_b").style.fontWeight = 900;
  document.getElementById("art_b").innerHTML = "Artist Biography";              //Artist Bio
  document.getElementById("artist_bio").innerHTML = data.bio.summary;
  sendRequest_topAlbums(name_artist);             //send request to aa new function to retrieve Top Albums[artist name is the parameter]
}

//Function to retrieve the Top Alums of the artist from the server
function sendRequest_topAlbums(art_name){
  var req = new XMLHttpRequest();
  var method = "artist.getTopAlbums";           //method to the API to get Top Albums
  var artist = art_name;
  //here set the limit of the records to retrie to n, here n is set to 7 by default[limit=7]
  req.open("GET", "proxy.php?method="+method+"&artist="+art_name+"&limit=7&api_key="+api_key+"&format=json", true);
  req.setRequestHeader("Accept","application/json");
  req.onreadystatechange = function () {
      if (this.readyState == 4) {
          var dec = JSON.parse(this.responseText);
          var dec_str = JSON.stringify(dec,undefined,2);
          var dec_artist = dec.artist;
          artist_albums(dec,art_name);        //pass the javascript oject parsed from the JSON to display function

      }
  };
  req.send(null);
}


//function to display the Top albums of thr artist along with the images.
function artist_albums(dec,artsists){
  document.getElementById("art_alb").style.fontWeight = 900;
  document.getElementById("art_alb").innerHTML = "Top Albums of the Artist";
  var list = document.getElementById("top_albums");
  //loop to iterate through all the records to display the images and the names of the albums
  for(var i =0; i < dec.topalbums.album.length; i++)
  {
    var mg = document.createElement("img");                         //create a tag
    mg.src = dec.topalbums.album[i].image[2]["#text"];              //get the image for the associated album
    mg.id = "picture";
    var alb_img = document.getElementById("album_img");
    album_img.appendChild(mg);                                     //display the image
    var name =  dec.topalbums.album[i].name;                        //retrieve the name
    var item = document.createElement('li');
    item.appendChild(document.createTextNode(name));
    list.appendChild(item);                                       //display the name in a list
  }
  sendRequest_similar_Artists(artsists);                          //call a new function to display the similar artists
}

//Function to retrieve the Similar Artists of the artist from the server
function sendRequest_similar_Artists(art_name){
  var sim = new XMLHttpRequest();
  var method = "artist.getSimilar";               //method for API call
  var artist = art_name;                          // taken as a parameter
  //here set the limit of the records to retrie to n, here n is set to 7 by default[limit=7]
  sim.open("GET", "proxy.php?method="+method+"&artist="+art_name+"&limit=7&api_key="+api_key+"&format=json", true);
  sim.setRequestHeader("Accept","application/json");
  sim.onreadystatechange = function () {
      if (this.readyState == 4) {
          var dec = JSON.parse(this.responseText);
          var dec_str = JSON.stringify(dec,undefined,2);
          var dec_artist = dec.artist;
          similar_artists(dec);           //call function to display the names

      }
  };
  sim.send(null);
}

//function to display the Similar Artists.
function similar_artists(simArt){
  document.getElementById("sim_tag").style.fontWeight = 900;
  document.getElementById("sim_tag").innerHTML = "Similar Artists";
  var list = document.getElementById("sim_art");
    //loop to iterate through all the records to display names of the artists
  for(var s=0; s < simArt.similarartists.artist.length; s++){
    var sim_name = simArt.similarartists.artist[s].name;
    var item = document.createElement('li');
    item.appendChild(document.createTextNode(sim_name));
    list.appendChild(item);
  }
}
