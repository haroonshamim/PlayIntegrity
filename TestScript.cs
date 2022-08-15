using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
public class TestScript : MonoBehaviour
{
    public Text Result;
    void Start()
    {
        
    }

    // Update is called once per frame
    void Update()
    {
        
    }

    IEnumerator CheckIntegrity(string sku)
    {
        bool VerdictResult = true; ;
        yield return StartCoroutine(IntegrityApiManager.IntegrityManagerInstance.StartCheck(r => VerdictResult = r));

        Debug.Log("RESULT:" + VerdictResult);

    

        //if (IntegrityApiManager.IntegrityManagerInstance.IntegrityResult)
        //    Result.text = "TEST PASSED" ;
        //else if (IntegrityApiManager.IntegrityManagerInstance.IsIntegrityCheckDone==false)
        //    Result.text = "TEST NOT ABLE TO COMPLETE";
        //else if (IntegrityApiManager.IntegrityManagerInstance.IsIntegrityCheckDone == true && IntegrityApiManager.IntegrityManagerInstance.IntegrityResult==false)
        //    Result.text = "TEST FAILED";

        if (VerdictResult)
        {
            Result.text = "TEST PASSED";
        }
        else
        {
            Result.text = "You Can not Access IAP";
        }


    }

    public void StartCheckNow()
    {

        StartCoroutine(CheckIntegrity("Testing"));

    }
}
