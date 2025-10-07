import{r as c,j as m}from"./app-DdCr8see.js";import{c as K,L as T,I as M,B as Q}from"./label-CgpcxaxB.js";import{C as V,a as G}from"./card-DmfCpCqM.js";import{L as J}from"./apis-CSkXTLdj.js";let X={data:""},ee=e=>typeof window=="object"?((e?e.querySelector("#_goober"):window._goober)||Object.assign((e||document.head).appendChild(document.createElement("style")),{innerHTML:" ",id:"_goober"})).firstChild:e||X,te=/(?:([\u0080-\uFFFF\w-%@]+) *:? *([^{;]+?);|([^;}{]*?) *{)|(}\s*)/g,ae=/\/\*[^]*?\*\/|  +/g,R=/\n+/g,w=(e,t)=>{let a="",r="",o="";for(let i in e){let s=e[i];i[0]=="@"?i[1]=="i"?a=i+" "+s+";":r+=i[1]=="f"?w(s,i):i+"{"+w(s,i[1]=="k"?"":t)+"}":typeof s=="object"?r+=w(s,t?t.replace(/([^,])+/g,n=>i.replace(/([^,]*:\S+\([^)]*\))|([^,])+/g,l=>/&/.test(l)?l.replace(/&/g,n):n?n+" "+l:l)):i):s!=null&&(i=/^--/.test(i)?i:i.replace(/[A-Z]/g,"-$&").toLowerCase(),o+=w.p?w.p(i,s):i+":"+s+";")}return a+(t&&o?t+"{"+o+"}":o)+r},b={},_=e=>{if(typeof e=="object"){let t="";for(let a in e)t+=a+_(e[a]);return t}return e},se=(e,t,a,r,o)=>{let i=_(e),s=b[i]||(b[i]=(l=>{let u=0,d=11;for(;u<l.length;)d=101*d+l.charCodeAt(u++)>>>0;return"go"+d})(i));if(!b[s]){let l=i!==e?e:(u=>{let d,p,g=[{}];for(;d=te.exec(u.replace(ae,""));)d[4]?g.shift():d[3]?(p=d[3].replace(R," ").trim(),g.unshift(g[0][p]=g[0][p]||{})):g[0][d[1]]=d[2].replace(R," ").trim();return g[0]})(e);b[s]=w(o?{["@keyframes "+s]:l}:l,a?"":"."+s)}let n=a&&b.g?b.g:null;return a&&(b.g=b[s]),((l,u,d,p)=>{p?u.data=u.data.replace(p,l):u.data.indexOf(l)===-1&&(u.data=d?l+u.data:u.data+l)})(b[s],t,r,n),s},re=(e,t,a)=>e.reduce((r,o,i)=>{let s=t[i];if(s&&s.call){let n=s(a),l=n&&n.props&&n.props.className||/^go/.test(n)&&n;s=l?"."+l:n&&typeof n=="object"?n.props?"":w(n,""):n===!1?"":n}return r+o+(s??"")},"");function L(e){let t=this||{},a=e.call?e(t.p):e;return se(a.unshift?a.raw?re(a,[].slice.call(arguments,1),t.p):a.reduce((r,o)=>Object.assign(r,o&&o.call?o(t.p):o),{}):a,ee(t.target),t.g,t.o,t.k)}let H,S,P;L.bind({g:1});let v=L.bind({k:1});function ie(e,t,a,r){w.p=t,H=e,S=a,P=r}function j(e,t){let a=this||{};return function(){let r=arguments;function o(i,s){let n=Object.assign({},i),l=n.className||o.className;a.p=Object.assign({theme:S&&S()},n),a.o=/ *go\d+/.test(l),n.className=L.apply(a,r)+(l?" "+l:"");let u=e;return e[0]&&(u=n.as||e,delete n.as),P&&u[0]&&P(n),H(u,n)}return t?t(o):o}}var oe=e=>typeof e=="function",C=(e,t)=>oe(e)?e(t):e,ne=(()=>{let e=0;return()=>(++e).toString()})(),B=(()=>{let e;return()=>{if(e===void 0&&typeof window<"u"){let t=matchMedia("(prefers-reduced-motion: reduce)");e=!t||t.matches}return e}})(),le=20,A="default",Y=(e,t)=>{let{toastLimit:a}=e.settings;switch(t.type){case 0:return{...e,toasts:[t.toast,...e.toasts].slice(0,a)};case 1:return{...e,toasts:e.toasts.map(s=>s.id===t.toast.id?{...s,...t.toast}:s)};case 2:let{toast:r}=t;return Y(e,{type:e.toasts.find(s=>s.id===r.id)?1:0,toast:r});case 3:let{toastId:o}=t;return{...e,toasts:e.toasts.map(s=>s.id===o||o===void 0?{...s,dismissed:!0,visible:!1}:s)};case 4:return t.toastId===void 0?{...e,toasts:[]}:{...e,toasts:e.toasts.filter(s=>s.id!==t.toastId)};case 5:return{...e,pausedAt:t.time};case 6:let i=t.time-(e.pausedAt||0);return{...e,pausedAt:void 0,toasts:e.toasts.map(s=>({...s,pauseDuration:s.pauseDuration+i}))}}},$=[],U={toasts:[],pausedAt:void 0,settings:{toastLimit:le}},y={},q=(e,t=A)=>{y[t]=Y(y[t]||U,e),$.forEach(([a,r])=>{a===t&&r(y[t])})},W=e=>Object.keys(y).forEach(t=>q(e,t)),ce=e=>Object.keys(y).find(t=>y[t].toasts.some(a=>a.id===e)),D=(e=A)=>t=>{q(t,e)},de={blank:4e3,error:4e3,success:2e3,loading:1/0,custom:4e3},ue=(e={},t=A)=>{let[a,r]=c.useState(y[t]||U),o=c.useRef(y[t]);c.useEffect(()=>(o.current!==y[t]&&r(y[t]),$.push([t,r]),()=>{let s=$.findIndex(([n])=>n===t);s>-1&&$.splice(s,1)}),[t]);let i=a.toasts.map(s=>{var n,l,u;return{...e,...e[s.type],...s,removeDelay:s.removeDelay||((n=e[s.type])==null?void 0:n.removeDelay)||e?.removeDelay,duration:s.duration||((l=e[s.type])==null?void 0:l.duration)||e?.duration||de[s.type],style:{...e.style,...(u=e[s.type])==null?void 0:u.style,...s.style}}});return{...a,toasts:i}},me=(e,t="blank",a)=>({createdAt:Date.now(),visible:!0,dismissed:!1,type:t,ariaProps:{role:"status","aria-live":"polite"},message:e,pauseDuration:0,...a,id:a?.id||ne()}),N=e=>(t,a)=>{let r=me(t,e,a);return D(r.toasterId||ce(r.id))({type:2,toast:r}),r.id},f=(e,t)=>N("blank")(e,t);f.error=N("error");f.success=N("success");f.loading=N("loading");f.custom=N("custom");f.dismiss=(e,t)=>{let a={type:3,toastId:e};t?D(t)(a):W(a)};f.dismissAll=e=>f.dismiss(void 0,e);f.remove=(e,t)=>{let a={type:4,toastId:e};t?D(t)(a):W(a)};f.removeAll=e=>f.remove(void 0,e);f.promise=(e,t,a)=>{let r=f.loading(t.loading,{...a,...a?.loading});return typeof e=="function"&&(e=e()),e.then(o=>{let i=t.success?C(t.success,o):void 0;return i?f.success(i,{id:r,...a,...a?.success}):f.dismiss(r),o}).catch(o=>{let i=t.error?C(t.error,o):void 0;i?f.error(i,{id:r,...a,...a?.error}):f.dismiss(r)}),e};var pe=1e3,fe=(e,t="default")=>{let{toasts:a,pausedAt:r}=ue(e,t),o=c.useRef(new Map).current,i=c.useCallback((p,g=pe)=>{if(o.has(p))return;let h=setTimeout(()=>{o.delete(p),s({type:4,toastId:p})},g);o.set(p,h)},[]);c.useEffect(()=>{if(r)return;let p=Date.now(),g=a.map(h=>{if(h.duration===1/0)return;let E=(h.duration||0)+h.pauseDuration-(p-h.createdAt);if(E<0){h.visible&&f.dismiss(h.id);return}return setTimeout(()=>f.dismiss(h.id,t),E)});return()=>{g.forEach(h=>h&&clearTimeout(h))}},[a,r,t]);let s=c.useCallback(D(t),[t]),n=c.useCallback(()=>{s({type:5,time:Date.now()})},[s]),l=c.useCallback((p,g)=>{s({type:1,toast:{id:p,height:g}})},[s]),u=c.useCallback(()=>{r&&s({type:6,time:Date.now()})},[r,s]),d=c.useCallback((p,g)=>{let{reverseOrder:h=!1,gutter:E=8,defaultPosition:z}=g||{},I=a.filter(x=>(x.position||z)===(p.position||z)&&x.height),Z=I.findIndex(x=>x.id===p.id),F=I.filter((x,O)=>O<Z&&x.visible).length;return I.filter(x=>x.visible).slice(...h?[F+1]:[0,F]).reduce((x,O)=>x+(O.height||0)+E,0)},[a]);return c.useEffect(()=>{a.forEach(p=>{if(p.dismissed)i(p.id,p.removeDelay);else{let g=o.get(p.id);g&&(clearTimeout(g),o.delete(p.id))}})},[a,i]),{toasts:a,handlers:{updateHeight:l,startPause:n,endPause:u,calculateOffset:d}}},ge=v`
from {
  transform: scale(0) rotate(45deg);
	opacity: 0;
}
to {
 transform: scale(1) rotate(45deg);
  opacity: 1;
}`,he=v`
from {
  transform: scale(0);
  opacity: 0;
}
to {
  transform: scale(1);
  opacity: 1;
}`,xe=v`
from {
  transform: scale(0) rotate(90deg);
	opacity: 0;
}
to {
  transform: scale(1) rotate(90deg);
	opacity: 1;
}`,ye=j("div")`
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
    animation: ${he} 0.15s ease-out forwards;
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
    animation: ${xe} 0.15s ease-out forwards;
    animation-delay: 180ms;
    transform: rotate(90deg);
  }
`,be=v`
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
`,ve=j("div")`
  width: 12px;
  height: 12px;
  box-sizing: border-box;
  border: 2px solid;
  border-radius: 100%;
  border-color: ${e=>e.secondary||"#e0e0e0"};
  border-right-color: ${e=>e.primary||"#616161"};
  animation: ${be} 1s linear infinite;
`,we=v`
from {
  transform: scale(0) rotate(45deg);
	opacity: 0;
}
to {
  transform: scale(1) rotate(45deg);
	opacity: 1;
}`,je=v`
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
}`,Ne=j("div")`
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
    animation: ${je} 0.2s ease-out forwards;
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
`,Ee=j("div")`
  position: absolute;
`,ke=j("div")`
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  min-width: 20px;
  min-height: 20px;
`,$e=v`
from {
  transform: scale(0.6);
  opacity: 0.4;
}
to {
  transform: scale(1);
  opacity: 1;
}`,Ce=j("div")`
  position: relative;
  transform: scale(0.6);
  opacity: 0.4;
  min-width: 20px;
  animation: ${$e} 0.3s 0.12s cubic-bezier(0.175, 0.885, 0.32, 1.275)
    forwards;
`,Le=({toast:e})=>{let{icon:t,type:a,iconTheme:r}=e;return t!==void 0?typeof t=="string"?c.createElement(Ce,null,t):t:a==="blank"?null:c.createElement(ke,null,c.createElement(ve,{...r}),a!=="loading"&&c.createElement(Ee,null,a==="error"?c.createElement(ye,{...r}):c.createElement(Ne,{...r})))},De=e=>`
0% {transform: translate3d(0,${e*-200}%,0) scale(.6); opacity:.5;}
100% {transform: translate3d(0,0,0) scale(1); opacity:1;}
`,Ie=e=>`
0% {transform: translate3d(0,0,-1px) scale(1); opacity:1;}
100% {transform: translate3d(0,${e*-150}%,-1px) scale(.6); opacity:0;}
`,Oe="0%{opacity:0;} 100%{opacity:1;}",Se="0%{opacity:1;} 100%{opacity:0;}",Pe=j("div")`
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
`,Ae=j("div")`
  display: flex;
  justify-content: center;
  margin: 4px 10px;
  color: inherit;
  flex: 1 1 auto;
  white-space: pre-line;
`,ze=(e,t)=>{let a=e.includes("top")?1:-1,[r,o]=B()?[Oe,Se]:[De(a),Ie(a)];return{animation:t?`${v(r)} 0.35s cubic-bezier(.21,1.02,.73,1) forwards`:`${v(o)} 0.4s forwards cubic-bezier(.06,.71,.55,1)`}},Fe=c.memo(({toast:e,position:t,style:a,children:r})=>{let o=e.height?ze(e.position||t||"top-center",e.visible):{opacity:0},i=c.createElement(Le,{toast:e}),s=c.createElement(Ae,{...e.ariaProps},C(e.message,e));return c.createElement(Pe,{className:e.className,style:{...o,...a,...e.style}},typeof r=="function"?r({icon:i,message:s}):c.createElement(c.Fragment,null,i,s))});ie(c.createElement);var Te=({id:e,className:t,style:a,onHeightUpdate:r,children:o})=>{let i=c.useCallback(s=>{if(s){let n=()=>{let l=s.getBoundingClientRect().height;r(e,l)};n(),new MutationObserver(n).observe(s,{subtree:!0,childList:!0,characterData:!0})}},[e,r]);return c.createElement("div",{ref:i,className:t,style:a},o)},Me=(e,t)=>{let a=e.includes("top"),r=a?{top:0}:{bottom:0},o=e.includes("center")?{justifyContent:"center"}:e.includes("right")?{justifyContent:"flex-end"}:{};return{left:0,right:0,display:"flex",position:"absolute",transition:B()?void 0:"all 230ms cubic-bezier(.21,1.02,.73,1)",transform:`translateY(${t*(a?1:-1)}px)`,...r,...o}},Re=L`
  z-index: 9999;
  > * {
    pointer-events: auto;
  }
`,k=16,_e=({reverseOrder:e,position:t="top-center",toastOptions:a,gutter:r,children:o,toasterId:i,containerStyle:s,containerClassName:n})=>{let{toasts:l,handlers:u}=fe(a,i);return c.createElement("div",{"data-rht-toaster":i||"",style:{position:"fixed",zIndex:9999,top:k,left:k,right:k,bottom:k,pointerEvents:"none",...s},className:n,onMouseEnter:u.startPause,onMouseLeave:u.endPause},l.map(d=>{let p=d.position||t,g=u.calculateOffset(d,{reverseOrder:e,gutter:r,defaultPosition:t}),h=Me(p,g);return c.createElement(Te,{id:d.id,key:d.id,onHeightUpdate:u.updateHeight,className:d.visible?Re:"",style:h},d.type==="custom"?C(d.message,d):o?o(d):c.createElement(Fe,{toast:d,position:p}))}))};function He({className:e,...t}){const[a,r]=c.useState(""),[o,i]=c.useState(""),[s,n]=c.useState(!1),l=async u=>{u.preventDefault(),n(!0);try{const d=await J({email:a,password:o});console.log(d),d.data.message==="Username or password is incorrect"?f.error("Username or password is incorrect"):d.data.status==="warning"?f.error("You aren't registered with us."):d.data.message==="Your account currently de-activated, please contact administrator"?f.error("Your account currently de-activated, please contact administrator"):(f.success("Login Success"),location.reload())}catch(d){f.error(d.response?.data?.message||"Something went wrong")}finally{n(!1)}};return m.jsxs("div",{className:K("flex flex-col gap-6",e),...t,children:[m.jsx(V,{className:"overflow-hidden",children:m.jsxs(G,{className:"grid p-0 md:grid-cols-2",children:[m.jsx("div",{className:"relative hidden bg-muted md:block",children:m.jsx("img",{src:"/placeholder.svg",alt:"Image",className:"absolute inset-0 h-full w-full object-cover dark:brightness-[0.2] dark:grayscale"})}),m.jsx("form",{className:"p-6 md:p-8",onSubmit:l,children:m.jsxs("div",{className:"flex flex-col gap-6",children:[m.jsxs("div",{className:"flex flex-col items-center text-center",children:[m.jsx("h1",{className:"text-2xl font-bold",children:"Welcome back"}),m.jsx("p",{className:"text-balance text-muted-foreground",children:"Login to your Acme Inc account"})]}),m.jsxs("div",{className:"grid gap-2",children:[m.jsx(T,{htmlFor:"email",children:"Email"}),m.jsx(M,{id:"email",type:"email",placeholder:"m@example.com",value:a,onChange:u=>r(u.target.value),required:!0})]}),m.jsxs("div",{className:"grid gap-2",children:[m.jsxs("div",{className:"flex items-center justify-between",children:[m.jsx(T,{htmlFor:"password",children:"Password"}),m.jsx("a",{href:"#",className:"ml-auto text-sm underline-offset-2 hover:underline",children:"Forgot your password?"})]}),m.jsx(M,{id:"password",type:"password",value:o,onChange:u=>i(u.target.value),required:!0})]}),m.jsx(Q,{type:"submit",className:"w-full",disabled:s,children:s?"Logging in...":"Login"}),m.jsxs("div",{className:"text-center text-sm",children:["Don't have an account?"," ",m.jsx("a",{href:"#",className:"underline underline-offset-4",children:"Sign up"})]})]})})]})}),m.jsxs("div",{className:"text-balance text-center text-xs text-muted-foreground [&_a]:underline [&_a]:underline-offset-4 hover:[&_a]:text-primary",children:["By clicking continue, you agree to our ",m.jsx("a",{href:"#",children:"Terms of Service"})," ","and ",m.jsx("a",{href:"#",children:"Privacy Policy"}),"."]}),m.jsx(_e,{})," "]})}function We(){return m.jsx("div",{className:"flex min-h-screen w-full flex-col items-center justify-center p-6 md:p-10",children:m.jsx("div",{className:"max-w-sm md:max-w-3xl",children:m.jsx(He,{})})})}export{We as default};
