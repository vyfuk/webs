from os.path import join, dirname
from os import getcwd, makedirs
from html.parser import HTMLParser
from urllib.parse import urljoin, urlsplit
import requests

netLocs=set()

outDir="temp/DownloadedPages/"
cacheDir="temp/"
urls={"http://vyfuk.org", "http://online.fyziklani.cz", "http://online.fyziklani.org", "http://fyziklani.cz", "http://fyziklani.org", "http://dsef.cz", "http://dsef.org", "http://fykos.cz", "http://fykos.org"}

for url in urls:
    netLocs.add(urlsplit(url).netloc)

class MyHTMLParser(HTMLParser):
    url:'str'
    links=set()
    def startNew(self, url):
        self.url=url
        self.links=set()
        self.reset()
    def handle_startendtag(self, tag, attrs):
        self.getUrls(attrs)        
    def handle_starttag(self, tag, attrs):
        self.getUrls(attrs)
    def getUrls(self,attrs):
        for name, value in attrs:
            if name == "href":
                self.links.add(urljoin(self.url,value))
    def analysePage(self,url):
        req=requests.get(url)
        if not req.ok:
            raise Exception('not OK ('+req.status_code+') '+req.reason)
        text=req.text
        self.startNew(req.url)
        if "html" in req.headers.get('Content-Type'):
            self.feed(text)
            self.validateHTML(text)
        return self.links
    
    def validateHTML(self, text):
        filename=outDir + urlsplit(url).netloc + urlsplit(url).path
        if not filename.endswith(".html"):
            filename=filename+".html"
        filename=join(getcwd(),filename)
        makedirs(dirname(filename))
            
        f = open(filename, "w")
        f.write(text)
        f.close()

def mainloop(urls, doneUrls, badUrls):
    for url in urls:
        if not url in doneUrls:
            if urlsplit(url).netloc in netLocs:
                try:
                    urls.update(parser.analysePage(url))
                except:
                    badUrls.add(url)
            else:
                pass
            doneUrls.add(url)
            return True
    return False


doneUrls=set()
badUrls=set()
changetUrls=set()
parser = MyHTMLParser()

while mainloop(urls,doneUrls,badUrls):
    pass

print("\n\n\n\nbad urls\n\n")
print(badUrls)
print("\n\n\n\nurls\n\n")
print(urls)
print("\n\n\n\nchanged urls\n\n")
print(changetUrls)