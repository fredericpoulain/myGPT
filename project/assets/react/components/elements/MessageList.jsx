import React, { useEffect } from "react";
import DOMPurify from "dompurify";
import Loader from "./svg/Loader";
import OpenAILogo from "./svg/OpenAILogo";

export default function MessageList({ isAddingMessage, messages }) {
    useEffect(() => {
        // Appelle highlightAll après que le contenu a été mis à jour
        hljs.highlightAll();
    }, [messages]); // Dépendance au tableau de messages

    return (
        <div className="messages">
            {messages && Array.isArray(messages) && messages.length > 0 ? (
                messages.map((msg, index) => (
                    <div key={index}>
                        <div className="userMessage message">
                            <div className="messageDiv">
                                <div className="messageProfilePic">
                                    <i className="fa-solid fa-user"></i>
                                </div>
                                <div className="messageContent messageContentUser">
                                    {msg.userMessage}
                                </div>
                            </div>
                        </div>
                        {msg.messageGpt === null ? (
                            <Loader />
                        ) : (
                            <div className="gptMessage message">
                                <div className="messageDiv">
                                    <div className="messageContent"
                                         dangerouslySetInnerHTML={{ __html: DOMPurify.sanitize(msg.messageGpt) }}>
                                    </div>
                                </div>
                            </div>
                        )}
                    </div>
                ))
            ) : (
                <OpenAILogo />
            )}
        </div>
    );
}
