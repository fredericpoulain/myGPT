import React, { useState } from "react";

export default function InputSection({ addMessage }) {
    const [message, setMessage] = useState("");

    const handleSubmit = (e) => {
        e.preventDefault();
        if (message.trim() !== "") {
            addMessage(message);
            setMessage("");
        }
    };

    return (
        <div className="inputSection">
            <form className="formAddMessage" onSubmit={handleSubmit}>
                <textarea
                    rows="4"
                    value={message}
                    onChange={(e) => setMessage(e.target.value)}
                ></textarea>
                <div className="submitIcon">
                    <button type="submit">
                        <i className="fa-solid fa-turn-up"></i>
                    </button>
                </div>
            </form>
        </div>
    );
}
