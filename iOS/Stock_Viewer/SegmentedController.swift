//
//  SegmentedController.swift
//  Stock_Viewer
//
//  Created by Hesen Zhang on 5/23/16.
//  Copyright © 2016 Hesen Zhang. All rights reserved.
//

//import Foundation
import UIKit
class SegmentedController: UIViewController {

   // var stock: Stock?
    
    @IBOutlet weak var segmentedController: UISegmentedControl!
    @IBOutlet weak var containerView: UIView!
    var stock : Stock?
    var detailedVC: DetailViewController?
    var historicalVC: UIViewController?
    var newsVC: UIViewController?
    
    @IBAction func segmentedValueChaned(sender: AnyObject) {
        switch segmentedController.selectedSegmentIndex {
        case 0:
            containerView.bringSubviewToFront((detailedVC?.view)!)
            break
        case 1:
            containerView.bringSubviewToFront((historicalVC?.view)!)
            break
        case 2:
            containerView.bringSubviewToFront((newsVC?.view)!)
            break
        default:
            break
        }
        
    }
    

    override func viewDidLoad() {
        super.viewDidLoad()
        detailedVC = storyboard?.instantiateViewControllerWithIdentifier("detailedViewController") as? DetailViewController
        historicalVC = storyboard?.instantiateViewControllerWithIdentifier("historicalViewController")
        newsVC = storyboard?.instantiateViewControllerWithIdentifier("newsViewController")
        detailedVC?.stock = self.stock
        
        containerView.addSubview((historicalVC?.view)!)
        containerView.addSubview((newsVC?.view)!)
        containerView.addSubview((detailedVC?.view)!)
        detailedVC?.stockChartImageView.image = UIImage(data: (self.stock?.getStockChartData())!)
        
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }

    
}