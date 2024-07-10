import React from "react";

export default function SideBar() {

    return (
        <>
            <div className="sidenav">
                <div className="sidenavContent">
                    <button className="close"><span></span></button>
                    <div className="mainSidebar">
                        <a href="/resetSession">
                            <button className="newChatBtn">
                                <i className="fa-regular fa-comments"></i> Nouveau
                            </button>
                        </a>
                        <div className="savedChats">
                            <h2>Historique</h2>


                        </div>

                    </div>

                    <div className="resetSession">
                        <hr/>
                        <a href="/connexion">Se Connecter</a>
                    </div>
                </div>
            </div>
        </>
    )
}