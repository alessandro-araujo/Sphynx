function logRequest(method, route) {
    fetch("https://api64.ipify.org?format=json")
        .then(response => response.json())
        .then(data => {
            fetch("http://localhost:8000/logs", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    method: method,
                    route: route,
                    ip: data.ip,
                    user_agent: navigator.userAgent
                })
            });
        });
}