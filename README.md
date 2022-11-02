
# File Converter Backend

This is the backend for the file converter which can convert any file that is supported by CloudConverter [CloudConverter](https://cloudconvert.com/) API.

[**Click here for the frontend.**](https://github.com/Lakshan-Madushanka/converter-frontend)
 
 ## Technologies used :
 - Laravel
 - Vue.js
 - [CloudConverter](https://cloudconvert.com/) API
 - Any web socket server supported by Laravel

## Installation
 ### Backend
  

 - Install the way any laravel application installs. 
 - Get a [CloudConverter](https://cloudconvert.com/) API token. if you use a sandbox account you need to while listing your files that need to be converted in the dashboard.
 - Include cloud converter credentals in the following .env file variables,
 - CLOUDCONVERT_API_KEY
 - CLOUDCONVERT_WEBHOOK_SIGNING_SECRET
 - CLOUDCONVERT_SANDBOX (true or false)
 - [Setup a WebSocket connection](https://laravel.com/docs/9.x/broadcasting).
 - Optionally you can set up a queue connection.
 - Run the server. Make sure the frontend and backend share the same domain port numbers and can be changed as sanctum is used to authenticate. Refer to [sanctum](https://laravel.com/docs/9.x/sanctum) documentation and follow instructions.

## Fronend

 - Install the project as a normal vue.js project install.
 - Using npm
```
npm install
npm run serve
```
## Screenshots
![converter3](https://user-images.githubusercontent.com/47297673/199537450-425cf0c7-ff47-4d7a-bed3-730f2317fb4a.PNG)

![converter1](https://user-images.githubusercontent.com/47297673/199537617-d0cf1ad6-fc7d-4c08-ae5f-afb2d4cbe57e.PNG)

![converter2](https://user-images.githubusercontent.com/47297673/199537701-d8420eb6-0f6d-4a18-b87e-745e757e865d.PNG)
