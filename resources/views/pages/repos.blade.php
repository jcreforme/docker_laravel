<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Test</title>

        
    </head>
    <body>
        <h1>{{$title}}</h1>
        <button id="btnCommits">Commits</button>
        <button id="btnGetInfo">Get Info</button>
        <div id="diveResult">
        <!-- result list here -->
        </div>
        <script>
        function clear() {
            while(divResult.firstChild)
            divResult.removeChild(divResult.firstChild)

           
        }
        
        const btnCommits = document.getElementById("btnCommits");
        const divResult = document.getElementById("diveResult");
        btnCommits.addEventListener("click", e => getCommits() );
        async function getCommits (url = "https://api.github.com/search/commits?q=repo:laravel/laravel author-date:>2019-12-01") {
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

        }


        const btnGetInfo = document.getElementById("btnGetInfo");
        btnGetInfo.addEventListener("click", e => btnGetInfos() );
        async function btnGetInfos (url = "https://api.github.com/users/laravel") {

            clear();
                
            const headers = {
                "Accept" : "application/vnd.github.cloak-preview"
            }
            const response = await fetch(url, {
                "method"  : "GET",
                "headers" : headers
            });

            //<https://api.github.com/search/commits?q=repo%3Afreecodecamp%2Ffreecodecamp+author-date%3A2019-03-01..2019-03-31&page=2>; rel="next", <https://api.github.com/search/commits?q=repo%3Afreecodecamp%2Ffreecodecamp+author-date%3A2019-03-01..2019-03-31&page=28>; rel="last"

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

        }

        
        </script>
    </body>
</html>
