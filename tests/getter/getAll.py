from os.path import join, dirname, exists
from os import getcwd, makedirs, system
from html.parser import HTMLParser
from urllib.parse import urljoin, urlsplit
import requests
from subprocess import run

netLocs=set()

outDir="temp/DownloadedPages/"
cacheDir="temp/"
urls={"http://vyfuk.org", "http://online.fyziklani.cz", "http://online.fyziklani.org", "http://fyziklani.cz", "http://fyziklani.org", "http://dsef.cz", "http://dsef.org", "http://fykos.cz", "http://fykos.org"}

for theurl in urls:
    netLocs.add(urlsplit(theurl).netloc)

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
            if name == "href" or name == "src":
                self.links.add(urljoin(self.url,value))
    def analysePage(self,url):
        req=requests.get(url)
        text=req.text
        self.startNew(req.url)
        if not req.ok:
            raise Exception('not OK ('+str(req.status_code)+') '+req.reason)
        if "html" in req.headers.get('Content-Type'):
            self.validateHTML(text)
            self.feed(text)
        return self.links
    
    def validateHTML(self, text):
        filename=outDir + urlsplit(self.url).netloc + urlsplit(self.url).path
        if not filename.endswith(".html"):
            filename=filename+".html"
        filename=join(getcwd(),filename)
        if not exists(dirname(filename)):
            makedirs(dirname(filename))
            
        f = open(filename, "w")
        f.write(text)
        f.close()

def mainloop(urls, doneUrls, badUrls):
    for url in urls:
        if not url in doneUrls:
            if urlsplit(url).netloc in netLocs:
                ourUrls.add(url)
                try:
                    urls.update(parser.analysePage(url))
                except:
                    badUrls.add(url)
            elif urlsplit(url).scheme == "mailto":
                emails.add(url)
            else:
                otherUrls.add(url)
            doneUrls.add(url)
            return True
    return False


otherUrls=set()
doneUrls=set()
badUrls=set()
ourUrls=set()
emails=set()
parser = MyHTMLParser()

while mainloop(urls,doneUrls,badUrls):
    pass

result=system("java -jar node_modules/vnu-jar/build/dist/vnu.jar " + outDir)


print("\n\n\nour urls\n______________________________________________________________")
for i in sorted(ourUrls):
    print(i)
print("\n\nother urls\n______________________________________________________________")
for i in sorted(otherUrls):
    print(i)
print("\n\nemails\n______________________________________________________________")
for i in sorted(emails):
    print(i)
print("\n\nbad urls\n______________________________________________________________")
for i in sorted(badUrls):
    print(i)


if len(badUrls)>0:
    exit(1)
exit(0)