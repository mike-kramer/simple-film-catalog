var glob = require('glob');
var rimraf = require('rimraf');
const path = require('path');

let publicPath = path.join(__dirname, "../../../public");
console.log(publicPath);

glob(`${publicPath}/*`, {}, function (err, paths) {
    console.log(paths);
    paths.map(function map(item) {
      if (["index.php", ".htaccess", "storage", "robots.txt"].indexOf(path.basename(item)) === -1) {
        rimraf.sync(item);
      }
    });
  });
