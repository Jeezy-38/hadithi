const {test} = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
function ui(standalone=false, secure=true) {
 const events={}, nodes={};
 for(const id of ['pwa-install','pwa-dialog','pwa-status','pwa-update','pwa-close','pwa-confirm']) nodes[id]={hidden:true,events:{},addEventListener(t,f){this.events[t]=f},showModal(){this.open=true},close(){this.open=false}};
 const window={isSecureContext:secure,matchMedia:()=>({matches:standalone,addEventListener(){}}),addEventListener:(t,f)=>events[t]=f};
 vm.runInNewContext(fs.readFileSync('public/js/pwa.js','utf8'),{window,navigator:{},document:{getElementById:id=>nodes[id]},URL});
 return {events,nodes};
}
test('manual install opens and closes instructions', async()=>{const {nodes}=ui();assert.equal(nodes['pwa-install'].hidden,false);await nodes['pwa-install'].events.click();assert.equal(nodes['pwa-dialog'].open,true);nodes['pwa-close'].events.click();assert.equal(nodes['pwa-dialog'].open,false)});
test('installed app hides installation button',()=>assert.equal(ui(true).nodes['pwa-install'].hidden,true));
test('insecure hosting explains HTTPS requirement',()=>assert.match(ui(false,false).nodes['pwa-status'].textContent,/HTTPS/));
test('native prompt accepted and dismissed flows',async()=>{for(const outcome of ['accepted','dismissed']){const {events,nodes}=ui();let called=0;events.beforeinstallprompt({preventDefault(){},async prompt(){called++},userChoice:Promise.resolve({outcome})});await nodes['pwa-install'].events.click();assert.equal(called,1);assert.equal(nodes['pwa-install'].hidden,outcome==='accepted')}});
test('offline navigation uses cached fallback; POST and audio are not intercepted',async()=>{
 const events={};const fallback=new Response('offline');const self={location:{href:'https://example.test/app/service-worker.js'},addEventListener:(t,f)=>events[t]=f};
 vm.runInNewContext(fs.readFileSync('public/service-worker.js','utf8'),{self,URL,Response,caches:{open:async()=>({match:async()=>fallback})},fetch:async()=>{throw Error('offline')}});
 let response;events.fetch({request:{method:'GET',url:'https://example.test/app/hadith/1',mode:'navigate'},respondWith:r=>response=r});assert.equal(await (await response).text(),'offline');
 for(const [method,url,mode] of [['POST','https://example.test/app/livewire/update','cors'],['GET','https://example.test/app/audio/hadith/1/sw','cors'],['GET','https://example.test/another/','navigate']]){let handled=false;events.fetch({request:{method,url,mode},respondWith:()=>handled=true});assert.equal(handled,false)}
});

test('installation popup opens automatically on entry',()=>assert.equal(ui().nodes['pwa-dialog'].open,true));
test('standalone app does not show automatic popup',()=>assert.notEqual(ui(true).nodes['pwa-dialog'].open,true));
