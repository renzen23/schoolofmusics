var parseStringSync = require('xml2js-parser').parseStringSync;
var fs = require( 'fs' );
var request = require( 'sync-request' );

var startUrl = 'http://schoolofmusic.test/sitemap_index.xml';
var baseUrl = startUrl.split('/').slice(0,-1).join('/')
var outputFile = './.pa11yci';
var defaultsFile = './.pa11ydefaults.json'

var getSitemapUrls = function( sitemapUrl, options ) {
  var urls = []

  if ( sitemapUrl.match( /^https?\:\/\// ) ) {
    console.log(`Extracting urls from ${sitemapUrl}`)

    var res = request( 'GET', sitemapUrl );

    try {
      const sitemap = parseStringSync(res.getBody().toString());

      if (!!sitemap.sitemapindex && !!sitemap.sitemapindex.sitemap && sitemap.sitemapindex.sitemap.length) {
        sitemap.sitemapindex.sitemap.forEach(entry => {
          if (!!entry.loc[0]) {
            urls = [...urls, ...getSitemapUrls(entry.loc[0])]
          }
        })
      } else if (!!sitemap.urlset){
        urls = sitemap.urlset.url.map(url => {
          if (url.loc[0].match(baseUrl)) {
            return url.loc[0]
          } else {
            return baseUrl + url.loc[0]
          }
        })
      }

      return urls

    } catch (err)  {
      console.error(err);
      return
    }
  }

  throw new Error( 'Please specify a valid URL' );
};

var urls = getSitemapUrls( startUrl );

var output = {
  urls: urls
}

try {
  var defaults = fs.readFileSync(defaultsFile, 'utf8')
  if (defaults) {
    output.default = JSON.parse(defaults)
  }
} catch (e) {}

try {
  fs.writeFileSync(outputFile, JSON.stringify(output, null, 2));
  console.log(`Wrote URLs to ${outputFile}`)
} catch(e) {
  console.error(`Error writing to ${outputFile}:`, e)
}
