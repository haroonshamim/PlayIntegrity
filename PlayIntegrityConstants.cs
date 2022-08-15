using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class PlayIntegrityConstants 
{
    public static string nonce;
    public static string Token;
    public static string Response;
    public static string url;
    public static string MobileID;
    public static string VerdictSavePref = "VERDICTDONE";
    public static string ErrorMessage = "ERROR FOUND";
    public static string UnEvaluatedMessage = "UNEVALUATED";
    public  const string DomainUrl= "https://apiintegrity.herokuapp.com/api/";
    public static string[] FailedVerdictsTypes = { "NONCE VERIFICATION FAILED", "Game not recognized", "User is not licensed to use app", "device doesn't meet requirement" };
    public static string[] CorrectVerdictsTypes = { "NONCE VERIFICATION PASSED", "Game is recognized", "User is licensed to use app", "device  meet requirement" };


}
