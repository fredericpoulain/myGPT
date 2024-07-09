import React, { useState, useEffect } from "react";
import { UseFetchChat } from "./UseFetchChat";
import ContentChat from "./ContentChat";
import NavMobile from "./elements/NavMobile";
import SideBar from "./elements/SideBar";
import { fetchDataFromServer } from "./utils/functions";

export function Home() {
    const [chat, setChat] = useState([]);
    const [isAddingMessage, setIsAddingMessage] = useState(false);
    const { loading, errors, fetchChat } = UseFetchChat('/getChat', setChat);

    const addMessage = async (message) => {
        setIsAddingMessage(true); // Démarrer le chargement

        const object = { message };
        const result = await fetchDataFromServer(object, '/addChat', 'POST');
        if (result.isSuccessfull) {
            setChat((prevChat) => {
                const updatedChat = [...prevChat];
                // Ajouter seulement le nouveau message reçu
                const newMessages = result.data.messages.slice(prevChat.length);
                return [...updatedChat, ...newMessages];
            });
        } else {
            // handle error
        }
        setIsAddingMessage(false); // Arrêter le chargement
    };

    useEffect(() => {
        fetchChat();
    }, []);

    return (
        <>
            {loading && 'chargement...!'}
            {errors && <div>{errors}</div>}
            <div>
                <NavMobile />
                <div className="main">
                    <SideBar />
                    <ContentChat isAddingMessage={isAddingMessage} chat={chat} addMessage={addMessage} />

                </div>
            </div>
        </>
    );
}
