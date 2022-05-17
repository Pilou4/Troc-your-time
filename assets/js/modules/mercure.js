const url = new URL('http://localhost:3006/.well-known/mercure');

url.searchParams.append('topic', 'http://troc-service.fr/ping');
// url.searchParams.append('topic', 'https://example.com/users/dunglas'); 
// The URL class is a convenient way to generate URLs such as https://localhost/.well-known/mercure?topic=https://example.com/books/{id}&topic=https://example.com/users/dunglas
const eventSource = new EventSource(url, {withCredentials: true});
// eventSource.onmessage = e => console.log(e);
// The callback will be called every time an update is published
eventSource.onmessage = e => {
    const data = JSON.parse(e.data);
    let message = "PING !";
    if (data.username) {
        message = `Vous avez reçu un message de ${data.username}`
    }
    
    let div = document.createElement('div');
    div.className = "alert alert-success";
    div.innerText = message;
    document.querySelector('.header').insertAdjacentElement('afterend', div);
    window.setTimeout(() => {
        const alert = document.querySelector('.alert');
        alert.parentNode.removeChild(alert)
    }, 3000);
} // do something with the payload