import React, { useState, useEffect } from "react";
import { UseFetchChat } from "./UseFetchChat";
import ContentChat from "./ContentChat";
import NavMobile from "./elements/NavMobile";
import SideBar from "./elements/SideBar";
import { fetchDataFromServer } from "./utils/functions";

export function Home() {
    const [chat, setChat] = useState([]);
    const [isAddingMessage, setIsAddingMessage] = useState(false);
    const [canScroll, setCanScroll] = useState(false);

    //***********************************************
    //uniquement lors du chargement de la page
    // On récupère les données de la session
    const { loading, errors, fetchChat } = UseFetchChat('/getChat', setChat);
    useEffect(() => {
        fetchChat();
    }, []);
    //***********************************************

    const addMessage = async (message) => {
        //on initialise avec un objet contenant le message de l'utilisateur, et celui de gtp à null car à se stade, la réponse n'est pas encore arrivée.
        const userMessage = { userMessage: message, messageGpt: null };
        //on place l'objet dans le 'useState'
        setChat((prevChat) => [...prevChat, userMessage]);

        // On démarre le chargement du 'Loader'
        setIsAddingMessage(true);

        //Envoi de la requête au serveur
        const object = { message };
        const result = await fetchDataFromServer(object, '/addChat', 'POST');

        //le serveur a répondu, on peut donc remplacer la valeur "null" par la réponse de chatGPT
        if (result.isSuccessfull) {
            setChat((prevChat) => {
                const updatedChat = [...prevChat];
                // console.log(updatedChat);
                const lastMessageIndex = updatedChat.length - 1;
                console.log(result.data.messages);
                console.log(prevChat.length - 1);
                const newMessageGpt = result.data.messages.slice(prevChat.length - 1)[0].messageGpt;
                updatedChat[lastMessageIndex].messageGpt = newMessageGpt;
                return updatedChat;
            });
        } else {
            // handle error
        }
        // On arrête le chargement du "Loader"
        setIsAddingMessage(false); // Arrêter le chargement
        setCanScroll(true)
    };

    return (
        <>
            {loading && 'chargement...!'}
            {errors && <div>{errors}</div>}
            <div>
                <NavMobile />
                <div className="main">
                    <SideBar />
                    <ContentChat isAddingMessage={isAddingMessage} chat={chat} addMessage={addMessage} canScroll={canScroll} setCanScroll={setCanScroll} />
                </div>
            </div>
        </>
    );
}
