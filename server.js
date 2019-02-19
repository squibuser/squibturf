var app = require('http').createServer(handler);
var io = require('socket.io')(app);


var fs = require('fs');


var options = {
  token: {
    key: "APNsAuthKey_58K59CD2F3.p8",
    keyId: "58K59CD2F3",
    teamId: "ED67MANCHB"
  },
  production: true
};


//for example purposes ---> replace body.to TO deviceToken

var deviceToken = "759b67a97e2d1ce18db26757aa0e907bacd2a1c8ffb382bf1d25fb563d0405a2";





//only using apple push notifications services. please use any dependiency for android
 
var apn = require('apn');

var apnProvider = new apn.Provider(options);




//var livereload = require('livereload');
var request = require('request');
var express  = require('express');
var cookieParser = require('cookie-parser');


app.listen(8080);




function handler (req, res) {
  fs.readFile(__dirname + '/html/index.html',
  function (err, data) {
    if (err) {
      res.writeHead(500);
      return res.end('Error loading index.html');
    }
	res.setEncoding('utf8');
    res.writeHead(200);
    res.end(data);
  });
}
function formatDate(month){
	var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
	return monthNames[month];
}


var usernames = {};



		        
		    
io.on('connection', function (socket) {
  
			//sen coords	
			socket.on('send:coords', function (data) { 
			    socket.userKey = data.key;
			    data['type'] = "user_info";	
			    request.post({ url: 'http://squibturf.com/server/server.php', json: true , form: data}, function(error, response, body){
		   			  if (body != undefined){
					     socket.broadcast.emit(body.emit, body);
					   }
			    });
			});
			
			
			
			// send squib
			socket.on('sendSquib', function(data){  
					data['type'] = "send_squib";	
					request.post({ url: 'http://squibturf.com/server/server.php', json: true , form: data}, function(error, response, body){
					   data['squib_id'] = body.squib_id;
					   io.sockets.emit(body.emit, data);
					});
				
			});
			
			
			
			// store squib
			socket.on('storeSquib', function(data){
					data['type'] = "store_squib";
					request.post({ url: 'http://squibturf.com/server/server.php', json: true , form: data}, function(error, response, body){
					      if(body.push_notification != undefined && body.to != null){		
						       		
									var note = new apn.Notification();
									note.expiry = Math.floor(Date.now() / 1000) + 3600; // Expires 1 hour from now.
									note.badge = 1;
									note.sound = "ping.aiff";
									note.alert = body.from+ "\n" + body.apnsMSG;
									note.payload = {'messageFrom': body.from};
									note.topic = "com.squib.turf"; apnProvider.send(note, body.to)
								}

					});
			
			});
			
			//store user
			socket.on('storeUser', function(data){
			
					data['type'] = "store_user";
					request.post({ url: 'http://squibturf.com/server/server.php', json: true , form: data}, function(error, response, body){
					      if(body.push_notification != undefined && body.to != null){		
						       		
									var note = new apn.Notification();
									note.expiry = Math.floor(Date.now() / 1000) + 3600; // Expires 1 hour from now.
									note.badge = 1;
									note.sound = "ping.aiff";
									note.alert =  body.apnsMSG;
									note.payload = {'messageFrom': body.from};
									note.topic = "com.squib.turf"; apnProvider.send(note, body.to)
									
								}
					});
			});   
			
			
			
			
			//send comment
			socket.on('sendComment', function(data){
			
					data['type'] = "store_comment";
					request.post({ url: 'http://squibturf.com/server/server.php', json: true , form: data}, function(error, response, body){
					       if(body.push_notification != undefined && body.to != null){		
						       		
									var note = new apn.Notification();
									note.expiry = Math.floor(Date.now() / 1000) + 3600; // Expires 1 hour from now.
									note.badge = 1;
									note.sound = "ping.aiff";
									note.alert = body.apnsMSG;
									note.payload = {'messageFrom': body.from};
									note.topic = "com.squib.turf"; apnProvider.send(note, body.to)
								}
		              	io.sockets.emit(body.emit, body);
					});
				
			});
			
			
			socket.on('deleteComment', function(data){
					request.post({ url: 'http://squibturf.com/server/server.php', json: true , form: data}, function(error, response, body){
					     	io.sockets.emit(body.emit, body);					
					});
			});
			
			
			socket.on('socketCall', function(data){
					request.post({ url: 'http://squibturf.com/server/server.php', json: true , form: data}, function(error, response, body){
					             if(body.push_notification != undefined && body.to != null){		
									var note = new apn.Notification();
									note.expiry = Math.floor(Date.now() / 1000) + 3600; // Expires 1 hour from now.
									note.badge = 1;
									note.sound = "ping.aiff";
									note.alert = body.apnsMSG;
									note.payload = {'messageFrom': body.from};
									note.topic = "com.squib.turf"; apnProvider.send(note, body.to)
								}
					       		socket.emit('responseCall', body);
					       		//io.sockets.emit(body.emit, body);
					});
			});
			
			
			//disconnect
			socket.on('disconnect', function(){
			     socket.broadcast.emit('disconnect-user', socket.userKey);
			
			});
			
			
			
			
			
			
}); 