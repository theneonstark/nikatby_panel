import{r as l,j as C}from"./app-CiXfCQw-.js";import{c as E}from"./apis-DcSPJLnN.js";const K=l.forwardRef(({className:e,...t},a)=>C.jsx("div",{ref:a,className:E("rounded-lg border bg-card text-card-foreground shadow-sm",e),...t}));K.displayName="Card";const Q=l.forwardRef(({className:e,...t},a)=>C.jsx("div",{ref:a,className:E("flex flex-col space-y-1.5 p-6",e),...t}));Q.displayName="CardHeader";const V=l.forwardRef(({className:e,...t},a)=>C.jsx("div",{ref:a,className:E("text-2xl font-semibold leading-none tracking-tight",e),...t}));V.displayName="CardTitle";const W=l.forwardRef(({className:e,...t},a)=>C.jsx("div",{ref:a,className:E("text-sm text-muted-foreground",e),...t}));W.displayName="CardDescription";const G=l.forwardRef(({className:e,...t},a)=>C.jsx("div",{ref:a,className:E("p-6 pt-0",e),...t}));G.displayName="CardContent";const J=l.forwardRef(({className:e,...t},a)=>C.jsx("div",{ref:a,className:E("flex items-center p-6 pt-0",e),...t}));J.displayName="CardFooter";let X={data:""},ee=e=>typeof window=="object"?((e?e.querySelector("#_goober"):window._goober)||Object.assign((e||document.head).appendChild(document.createElement("style")),{innerHTML:" ",id:"_goober"})).firstChild:e||X,te=/(?:([\u0080-\uFFFF\w-%@]+) *:? *([^{;]+?);|([^;}{]*?) *{)|(}\s*)/g,ae=/\/\*[^]*?\*\/|  +/g,L=/\n+/g,x=(e,t)=>{let a="",s="",i="";for(let o in e){let r=e[o];o[0]=="@"?o[1]=="i"?a=o+" "+r+";":s+=o[1]=="f"?x(r,o):o+"{"+x(r,o[1]=="k"?"":t)+"}":typeof r=="object"?s+=x(r,t?t.replace(/([^,])+/g,n=>o.replace(/([^,]*:\S+\([^)]*\))|([^,])+/g,d=>/&/.test(d)?d.replace(/&/g,n):n?n+" "+d:d)):o):r!=null&&(o=/^--/.test(o)?o:o.replace(/[A-Z]/g,"-$&").toLowerCase(),i+=x.p?x.p(o,r):o+":"+r+";")}return a+(t&&i?t+"{"+i+"}":i)+s},h={},M=e=>{if(typeof e=="object"){let t="";for(let a in e)t+=a+M(e[a]);return t}return e},re=(e,t,a,s,i)=>{let o=M(e),r=h[o]||(h[o]=(d=>{let u=0,p=11;for(;u<d.length;)p=101*p+d.charCodeAt(u++)>>>0;return"go"+p})(o));if(!h[r]){let d=o!==e?e:(u=>{let p,c,m=[{}];for(;p=te.exec(u.replace(ae,""));)p[4]?m.shift():p[3]?(c=p[3].replace(L," ").trim(),m.unshift(m[0][c]=m[0][c]||{})):m[0][p[1]]=p[2].replace(L," ").trim();return m[0]})(e);h[r]=x(i?{["@keyframes "+r]:d}:d,a?"":"."+r)}let n=a&&h.g?h.g:null;return a&&(h.g=h[r]),((d,u,p,c)=>{c?u.data=u.data.replace(c,d):u.data.indexOf(d)===-1&&(u.data=p?d+u.data:u.data+d)})(h[r],t,s,n),r},se=(e,t,a)=>e.reduce((s,i,o)=>{let r=t[o];if(r&&r.call){let n=r(a),d=n&&n.props&&n.props.className||/^go/.test(n)&&n;r=d?"."+d:n&&typeof n=="object"?n.props?"":x(n,""):n===!1?"":n}return s+i+(r??"")},"");function O(e){let t=this||{},a=e.call?e(t.p):e;return re(a.unshift?a.raw?se(a,[].slice.call(arguments,1),t.p):a.reduce((s,i)=>Object.assign(s,i&&i.call?i(t.p):i),{}):a,ee(t.target),t.g,t.o,t.k)}let S,I,T;O.bind({g:1});let v=O.bind({k:1});function oe(e,t,a,s){x.p=t,S=e,I=a,T=s}function w(e,t){let a=this||{};return function(){let s=arguments;function i(o,r){let n=Object.assign({},o),d=n.className||i.className;a.p=Object.assign({theme:I&&I()},n),a.o=/ *go\d+/.test(d),n.className=O.apply(a,s)+(d?" "+d:"");let u=e;return e[0]&&(u=n.as||e,delete n.as),T&&u[0]&&T(n),S(u,n)}return t?t(i):i}}var ie=e=>typeof e=="function",D=(e,t)=>ie(e)?e(t):e,ne=(()=>{let e=0;return()=>(++e).toString()})(),_=(()=>{let e;return()=>{if(e===void 0&&typeof window<"u"){let t=matchMedia("(prefers-reduced-motion: reduce)");e=!t||t.matches}return e}})(),le=20,F="default",B=(e,t)=>{let{toastLimit:a}=e.settings;switch(t.type){case 0:return{...e,toasts:[t.toast,...e.toasts].slice(0,a)};case 1:return{...e,toasts:e.toasts.map(r=>r.id===t.toast.id?{...r,...t.toast}:r)};case 2:let{toast:s}=t;return B(e,{type:e.toasts.find(r=>r.id===s.id)?1:0,toast:s});case 3:let{toastId:i}=t;return{...e,toasts:e.toasts.map(r=>r.id===i||i===void 0?{...r,dismissed:!0,visible:!1}:r)};case 4:return t.toastId===void 0?{...e,toasts:[]}:{...e,toasts:e.toasts.filter(r=>r.id!==t.toastId)};case 5:return{...e,pausedAt:t.time};case 6:let o=t.time-(e.pausedAt||0);return{...e,pausedAt:void 0,toasts:e.toasts.map(r=>({...r,pauseDuration:r.pauseDuration+o}))}}},$=[],U={toasts:[],pausedAt:void 0,settings:{toastLimit:le}},b={},Y=(e,t=F)=>{b[t]=B(b[t]||U,e),$.forEach(([a,s])=>{a===t&&s(b[t])})},Z=e=>Object.keys(b).forEach(t=>Y(e,t)),de=e=>Object.keys(b).find(t=>b[t].toasts.some(a=>a.id===e)),z=(e=F)=>t=>{Y(t,e)},ce={blank:4e3,error:4e3,success:2e3,loading:1/0,custom:4e3},ue=(e={},t=F)=>{let[a,s]=l.useState(b[t]||U),i=l.useRef(b[t]);l.useEffect(()=>(i.current!==b[t]&&s(b[t]),$.push([t,s]),()=>{let r=$.findIndex(([n])=>n===t);r>-1&&$.splice(r,1)}),[t]);let o=a.toasts.map(r=>{var n,d,u;return{...e,...e[r.type],...r,removeDelay:r.removeDelay||((n=e[r.type])==null?void 0:n.removeDelay)||e?.removeDelay,duration:r.duration||((d=e[r.type])==null?void 0:d.duration)||e?.duration||ce[r.type],style:{...e.style,...(u=e[r.type])==null?void 0:u.style,...r.style}}});return{...a,toasts:o}},pe=(e,t="blank",a)=>({createdAt:Date.now(),visible:!0,dismissed:!1,type:t,ariaProps:{role:"status","aria-live":"polite"},message:e,pauseDuration:0,...a,id:a?.id||ne()}),k=e=>(t,a)=>{let s=pe(t,e,a);return z(s.toasterId||de(s.id))({type:2,toast:s}),s.id},f=(e,t)=>k("blank")(e,t);f.error=k("error");f.success=k("success");f.loading=k("loading");f.custom=k("custom");f.dismiss=(e,t)=>{let a={type:3,toastId:e};t?z(t)(a):Z(a)};f.dismissAll=e=>f.dismiss(void 0,e);f.remove=(e,t)=>{let a={type:4,toastId:e};t?z(t)(a):Z(a)};f.removeAll=e=>f.remove(void 0,e);f.promise=(e,t,a)=>{let s=f.loading(t.loading,{...a,...a?.loading});return typeof e=="function"&&(e=e()),e.then(i=>{let o=t.success?D(t.success,i):void 0;return o?f.success(o,{id:s,...a,...a?.success}):f.dismiss(s),i}).catch(i=>{let o=t.error?D(t.error,i):void 0;o?f.error(o,{id:s,...a,...a?.error}):f.dismiss(s)}),e};var me=1e3,fe=(e,t="default")=>{let{toasts:a,pausedAt:s}=ue(e,t),i=l.useRef(new Map).current,o=l.useCallback((c,m=me)=>{if(i.has(c))return;let g=setTimeout(()=>{i.delete(c),r({type:4,toastId:c})},m);i.set(c,g)},[]);l.useEffect(()=>{if(s)return;let c=Date.now(),m=a.map(g=>{if(g.duration===1/0)return;let j=(g.duration||0)+g.pauseDuration-(c-g.createdAt);if(j<0){g.visible&&f.dismiss(g.id);return}return setTimeout(()=>f.dismiss(g.id,t),j)});return()=>{m.forEach(g=>g&&clearTimeout(g))}},[a,s,t]);let r=l.useCallback(z(t),[t]),n=l.useCallback(()=>{r({type:5,time:Date.now()})},[r]),d=l.useCallback((c,m)=>{r({type:1,toast:{id:c,height:m}})},[r]),u=l.useCallback(()=>{s&&r({type:6,time:Date.now()})},[s,r]),p=l.useCallback((c,m)=>{let{reverseOrder:g=!1,gutter:j=8,defaultPosition:P}=m||{},R=a.filter(y=>(y.position||P)===(c.position||P)&&y.height),q=R.findIndex(y=>y.id===c.id),H=R.filter((y,A)=>A<q&&y.visible).length;return R.filter(y=>y.visible).slice(...g?[H+1]:[0,H]).reduce((y,A)=>y+(A.height||0)+j,0)},[a]);return l.useEffect(()=>{a.forEach(c=>{if(c.dismissed)o(c.id,c.removeDelay);else{let m=i.get(c.id);m&&(clearTimeout(m),i.delete(c.id))}})},[a,o]),{toasts:a,handlers:{updateHeight:d,startPause:n,endPause:u,calculateOffset:p}}},ge=v`
from {
  transform: scale(0) rotate(45deg);
	opacity: 0;
}
to {
 transform: scale(1) rotate(45deg);
  opacity: 1;
}`,ye=v`
from {
  transform: scale(0);
  opacity: 0;
}
to {
  transform: scale(1);
  opacity: 1;
}`,be=v`
from {
  transform: scale(0) rotate(90deg);
	opacity: 0;
}
to {
  transform: scale(1) rotate(90deg);
	opacity: 1;
}`,he=w("div")`
  width: 20px;
  opacity: 0;
  height: 20px;
  border-radius: 10px;
  background: ${e=>e.primary||"#ff4b4b"};
  position: relative;
  transform: rotate(45deg);

  animation: ${ge} 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275)
    forwards;
  animation-delay: 100ms;

  &:after,
  &:before {
    content: '';
    animation: ${ye} 0.15s ease-out forwards;
    animation-delay: 150ms;
    position: absolute;
    border-radius: 3px;
    opacity: 0;
    background: ${e=>e.secondary||"#fff"};
    bottom: 9px;
    left: 4px;
    height: 2px;
    width: 12px;
  }

  &:before {
    animation: ${be} 0.15s ease-out forwards;
    animation-delay: 180ms;
    transform: rotate(90deg);
  }
`,ve=v`
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
`,xe=w("div")`
  width: 12px;
  height: 12px;
  box-sizing: border-box;
  border: 2px solid;
  border-radius: 100%;
  border-color: ${e=>e.secondary||"#e0e0e0"};
  border-right-color: ${e=>e.primary||"#616161"};
  animation: ${ve} 1s linear infinite;
`,we=v`
from {
  transform: scale(0) rotate(45deg);
	opacity: 0;
}
to {
  transform: scale(1) rotate(45deg);
	opacity: 1;
}`,Ce=v`
0% {
	height: 0;
	width: 0;
	opacity: 0;
}
40% {
  height: 0;
	width: 6px;
	opacity: 1;
}
100% {
  opacity: 1;
  height: 10px;
}`,Ee=w("div")`
  width: 20px;
  opacity: 0;
  height: 20px;
  border-radius: 10px;
  background: ${e=>e.primary||"#61d345"};
  position: relative;
  transform: rotate(45deg);

  animation: ${we} 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275)
    forwards;
  animation-delay: 100ms;
  &:after {
    content: '';
    box-sizing: border-box;
    animation: ${Ce} 0.2s ease-out forwards;
    opacity: 0;
    animation-delay: 200ms;
    position: absolute;
    border-right: 2px solid;
    border-bottom: 2px solid;
    border-color: ${e=>e.secondary||"#fff"};
    bottom: 6px;
    left: 6px;
    height: 10px;
    width: 6px;
  }
`,ke=w("div")`
  position: absolute;
`,je=w("div")`
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  min-width: 20px;
  min-height: 20px;
`,Ne=v`
from {
  transform: scale(0.6);
  opacity: 0.4;
}
to {
  transform: scale(1);
  opacity: 1;
}`,$e=w("div")`
  position: relative;
  transform: scale(0.6);
  opacity: 0.4;
  min-width: 20px;
  animation: ${Ne} 0.3s 0.12s cubic-bezier(0.175, 0.885, 0.32, 1.275)
    forwards;
`,De=({toast:e})=>{let{icon:t,type:a,iconTheme:s}=e;return t!==void 0?typeof t=="string"?l.createElement($e,null,t):t:a==="blank"?null:l.createElement(je,null,l.createElement(xe,{...s}),a!=="loading"&&l.createElement(ke,null,a==="error"?l.createElement(he,{...s}):l.createElement(Ee,{...s})))},Oe=e=>`
0% {transform: translate3d(0,${e*-200}%,0) scale(.6); opacity:.5;}
100% {transform: translate3d(0,0,0) scale(1); opacity:1;}
`,ze=e=>`
0% {transform: translate3d(0,0,-1px) scale(1); opacity:1;}
100% {transform: translate3d(0,${e*-150}%,-1px) scale(.6); opacity:0;}
`,Re="0%{opacity:0;} 100%{opacity:1;}",Ae="0%{opacity:1;} 100%{opacity:0;}",Ie=w("div")`
  display: flex;
  align-items: center;
  background: #fff;
  color: #363636;
  line-height: 1.3;
  will-change: transform;
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1), 0 3px 3px rgba(0, 0, 0, 0.05);
  max-width: 350px;
  pointer-events: auto;
  padding: 8px 10px;
  border-radius: 8px;
`,Te=w("div")`
  display: flex;
  justify-content: center;
  margin: 4px 10px;
  color: inherit;
  flex: 1 1 auto;
  white-space: pre-line;
`,Fe=(e,t)=>{let a=e.includes("top")?1:-1,[s,i]=_()?[Re,Ae]:[Oe(a),ze(a)];return{animation:t?`${v(s)} 0.35s cubic-bezier(.21,1.02,.73,1) forwards`:`${v(i)} 0.4s forwards cubic-bezier(.06,.71,.55,1)`}},Pe=l.memo(({toast:e,position:t,style:a,children:s})=>{let i=e.height?Fe(e.position||t||"top-center",e.visible):{opacity:0},o=l.createElement(De,{toast:e}),r=l.createElement(Te,{...e.ariaProps},D(e.message,e));return l.createElement(Ie,{className:e.className,style:{...i,...a,...e.style}},typeof s=="function"?s({icon:o,message:r}):l.createElement(l.Fragment,null,o,r))});oe(l.createElement);var He=({id:e,className:t,style:a,onHeightUpdate:s,children:i})=>{let o=l.useCallback(r=>{if(r){let n=()=>{let d=r.getBoundingClientRect().height;s(e,d)};n(),new MutationObserver(n).observe(r,{subtree:!0,childList:!0,characterData:!0})}},[e,s]);return l.createElement("div",{ref:o,className:t,style:a},i)},Le=(e,t)=>{let a=e.includes("top"),s=a?{top:0}:{bottom:0},i=e.includes("center")?{justifyContent:"center"}:e.includes("right")?{justifyContent:"flex-end"}:{};return{left:0,right:0,display:"flex",position:"absolute",transition:_()?void 0:"all 230ms cubic-bezier(.21,1.02,.73,1)",transform:`translateY(${t*(a?1:-1)}px)`,...s,...i}},Me=O`
  z-index: 9999;
  > * {
    pointer-events: auto;
  }
`,N=16,Be=({reverseOrder:e,position:t="top-center",toastOptions:a,gutter:s,children:i,toasterId:o,containerStyle:r,containerClassName:n})=>{let{toasts:d,handlers:u}=fe(a,o);return l.createElement("div",{"data-rht-toaster":o||"",style:{position:"fixed",zIndex:9999,top:N,left:N,right:N,bottom:N,pointerEvents:"none",...r},className:n,onMouseEnter:u.startPause,onMouseLeave:u.endPause},d.map(p=>{let c=p.position||t,m=u.calculateOffset(p,{reverseOrder:e,gutter:s,defaultPosition:t}),g=Le(c,m);return l.createElement(He,{id:p.id,key:p.id,onHeightUpdate:u.updateHeight,className:p.visible?Me:"",style:g},p.type==="custom"?D(p.message,p):i?i(p):l.createElement(Pe,{toast:p,position:c}))}))},Ue=f;export{K as C,Be as F,G as a,Q as b,V as c,W as d,f as n,Ue as z};
