import React from "react";

export default function InputSection({addMessage}){
    return (
        <>
            <div className="inputSection">
                    <form className="formAddMessage" action="">
                        <textarea rows="4"></textarea>
                        <div className="submitIcon">
                            <button type="submit" onClick={addMessage}>
                                <i className="fa-solid fa-turn-up"></i>
                            </button>
                        </div>
                    </form>
            </div>
        </>
    )
}