CleverData
==========

Basic class analysis of Clever Demo API data using Underscore.js

<h2>Step 1: Install Node.js and Underscore CLI</h2>
```
git clone git://github.com/ry/node.git
cd node
./configure
make
sudo make install
npm install -g underscore-cli
underscore help
```

<h2>Step 2: Clone the Repo</h2>
```
git clone https://github.com/joevasquez/cleverData.git
```

<h2>Step 3: Import the JSON</h2>
```
curl -H 'Authorization: Bearer DEMO_TOKEN' -X GET https://api.clever.com/v1.1/sections?limit=1000 | 
  underscore select '.data' -o assets/json/temp.json;
```

<h2>Step 4: View Locally</h2>
