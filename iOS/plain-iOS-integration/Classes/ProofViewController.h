//
//  ModalViewController.h
//  AdWhirlSDK2_Sample
//
//  Created by Nigel Choi on 3/11/10.
//  Copyright 2010 Admob. Inc. All rights reserved.
//

#import <UIKit/UIKit.h>


@interface ProofViewController : UIViewController<UIWebViewDelegate> {
  UIWebView *web;
  NSURL *url;
}
- (IBAction)dismiss:(id)sender;
- (id) initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil initWithParam:(NSURL *)url;
@property (nonatomic,retain) NSURL *url;

@end
