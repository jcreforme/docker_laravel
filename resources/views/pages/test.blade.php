@extends('layouts.app')

@section('content')
        <h1>{{$title}}</h1>
        <button id="btnRepos">Repos</button>
        <button id="btnIssues">Issues</button>
        <button id="btnCommits">Commits</button>
        <button id="btnPrivate">Private Issues</button>
        <button id="btnCreate">Private Create Issue</button>
        <div id="diveResult">
        <!-- result list here -->
        </div>
        <script>
        function clear() {
            while(divResult.firstChild)
            divResult.removeChild(divResult.firstChild)

           
        }

        const btnRepos = document.getElementById("btnRepos");
        const divResult = document.getElementById("diveResult");
        btnRepos.addEventListener("click", getRepos);
        async function getRepos () {
            clear();
            const url = "https://api.github.com/search/repositories?q=stars:100000..200000";
            const response = await fetch(url);
            const result = await response.json();

            result.items.forEach(i=>{
                const anchor = document.createElement("a")
                anchor.href = i.html_url;
                anchor.textContent = i.full_name;
                diveResult.appendChild(anchor)
                diveResult.appendChild(document.createElement("br"))
            });
        }
        </script>

        
        <script>
        const btnIssues = document.getElementById("btnIssues");
        btnIssues.addEventListener("click", getIssues);
        async function getIssues () {
            clear();
            const url = "https://api.github.com/search/issues?q=author:raisedadead repo:freecodecamp/freecodecamp type:issue";
            const response = await fetch(url);
            const result = await response.json();

            result.items.forEach(i=>{
                const anchor = document.createElement("a")
                anchor.href = i.html_url;
                anchor.textContent = i.title;
                divResult.appendChild(anchor)
                divResult.appendChild(document.createElement("br"))
            });
        }

        
        </script>

        
        <script>
        const btnCommits = document.getElementById("btnCommits");
        btnCommits.addEventListener("click", e => getCommits() );
        async function getCommits (url = "https://api.github.com/search/commits?q=repo:freecodecamp/freecodecamp author-date:2019-03-01..2019-03-31") {
            //const url = "https://api.github.com/search/commits?q=repo:freecodecamp/freecodecamp author-date:2019-03-01..2019-03-31";
            clear();
                
            const headers = {
                "Accept" : "application/vnd.github.cloak-preview"
            }
            const response = await fetch(url, {
                "method"  : "GET",
                "headers" : headers
            });

            //<https://api.github.com/search/commits?q=repo%3Afreecodecamp%2Ffreecodecamp+author-date%3A2019-03-01..2019-03-31&page=2>; rel="next", <https://api.github.com/search/commits?q=repo%3Afreecodecamp%2Ffreecodecamp+author-date%3A2019-03-01..2019-03-31&page=28>; rel="last"

            const link = response.headers.get("link");
            console.log(link);
            const links = link.split(",")
            const urls = links.map(a=>{
                return {
                    url: a.split(";")[0].replace(">","").replace("<",""),
                    title: a.split(";")[1]
                }
            })

            const result = await response.json();

            result.items.forEach(i=>{
                const anchor = document.createElement("a")
                const image =  document.createElement("img")
                image.src = i.author.avatar_url;
                image.style.width = "32px";
                image.style.height = "32px";
                anchor.href = i.html_url;
                anchor.textContent = i.commit.message.substr(0,120) + "...";
                divResult.appendChild(image)
                divResult.appendChild(anchor)
                divResult.appendChild(document.createElement("br"))
            });

            urls.forEach(u => {

                const btn = document.createElement("button")
                btn.textContent = u.title;
                btn.addEventListener("click", e=> getCommits(u.url));
                divResult.appendChild(btn);
            })
        }

        
        </script>

        
        <script>
        const btnPrivate = document.getElementById("btnPrivate");
        btnPrivate.addEventListener("click", e => getPrivate() );
        async function getPrivate (url = "https://api.github.com/search/issues?q=author:jcreforme repo:jcreforme/jcreforme type:issue") {
            
            //read_token  ee3829ca3fba0752ac7a0a0deb34f7978f99a838 
            clear();
               
            const headers = {
                "Accept" : "application/vnd.github.cloak-preview",
                "Authorization" : "Token ee3829ca3fba0752ac7a0a0deb34f7978f99a838"
            }
            const response = await fetch(url, {
                "method"  : "GET",
                "headers" : headers
            });

            const result = await response.json();

            result.items.forEach(i=>{
                 const anchor = document.createElement("a")
                anchor.href = i.html_url;
                anchor.textContent = i.title;
                divResult.appendChild(anchor)
                divResult.appendChild(document.createElement("br"))
            });
        }        
        </script>

        
        <script>
        const btnCreate = document.getElementById("btnCreate");
        btnCreate.addEventListener("click", e => createIssue() );
        async function createIssue () {
            
            clear();
            const url = "https://api.github.com/repos/jcreforme/jcreforme/issues";
            
            const headers = {
                "Authorization" : "Token ee3829ca3fba0752ac7a0a0deb34f7978f99a838"
            }
            const payLoad = {
                title : "my new fresh issue"
            }
            const response = await fetch(url, {
                method : "POST",
                headers : headers,
                body: JSON.stringify(payLoad)
            });
            
            const result = await response.json();

            const i = result; 

                const anchor = document.createElement("a")
                anchor.href = i.html_url;
                anchor.textContent = i.title;
                diveResult.appendChild(anchor)
                diveResult.appendChild(document.createElement("br"))
        }        
        </script>
@endsection