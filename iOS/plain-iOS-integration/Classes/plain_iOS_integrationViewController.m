//
//  plain_iOS_integrationViewController.m
//  plain-iOS-integration
//
//  Created by Ryan Matsumura on 3/21/11.
//  Copyright 2011 self. All rights reserved.
//

#import "plain_iOS_integrationViewController.h"
#import "ProofViewController.h"

@implementation plain_iOS_integrationViewController



/*
// The designated initializer. Override to perform setup that is required before the view is loaded.
- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
    }
    return self;
}
*/

/*
// Implement loadView to create a view hierarchy programmatically, without using a nib.
- (void)loadView {
}
*/


// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
- (void)viewDidLoad {
	[super viewDidLoad];
	//build up a webview for the 140 Proof Ad unit
	UIWebView *proofAd = [[UIWebView alloc] initWithFrame:CGRectMake(0, 0, 320, 50)];
 
	proofAd.delegate = self;
 
	//populate the webview
	//** TODO: YOU WILL NEED TO REPLACE THE APPROPRIATE PARAMETER VALUES WITH REAL VALUES (APP_ID, USER_ID, ETC. per http://publishers.140proof.com/docs)
	[proofAd loadRequest:[NSURLRequest requestWithURL:[NSURL URLWithString:@"http://api.140proof.com/acls/user.html?user_id=140ProofAds&app_id=1&style=sban&width=320&height=50"]]];
	//[proofAd loadRequest:[NSURLRequest requestWithURL:[NSURL URLWithString:@"http://localhost:3000/acls/user.html?user_id=140ProofAds&app_id=1&style=sban&width=320&height=50"]]];
	
	//show the webview
	[self.view addSubview:proofAd];
	[proofAd release];
}


/*
// Override to allow orientations other than the default portrait orientation.
- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation {
    // Return YES for supported orientations
    return (interfaceOrientation == UIInterfaceOrientationPortrait);
}
*/

- (void)didReceiveMemoryWarning {
	// Releases the view if it doesn't have a superview.
    [super didReceiveMemoryWarning];
	
	// Release any cached data, images, etc that aren't in use.
}

- (void)viewDidUnload {
	// Release any retained subviews of the main view.
	// e.g. self.myOutlet = nil;
}


- (void)dealloc {
    [super dealloc];
}

/**
 * PROOF click handler
 * - intercept clicks in the ad webview
 */
- (BOOL)webView:(UIWebView *)webView shouldStartLoadWithRequest:(NSURLRequest *)request
 navigationType:(UIWebViewNavigationType)navigationType; { 
	
	//Open links that start w/ http or https in a new view
	NSURL *requestURL =[ [ request URL ] retain ]; 
	// Check to see what protocol/scheme the requested URL is. 
	if ( ( [ [ requestURL scheme ] isEqualToString: @"http"  ] 
		  || [ [ requestURL scheme ] isEqualToString: @"https" ] ) 
		&& ( navigationType == UIWebViewNavigationTypeLinkClicked ) ) { 
		
		//If this is an external click, show the ProofView and load the requestURL in the provided webview
		ProofViewController *proofView = [[[ ProofViewController alloc] initWithNibName:@"ProofViewController" bundle:nil initWithParam:requestURL] autorelease]; 		
		NSLog(@"\n\n\n%@\n\n\n", self);
		[self presentModalViewController:proofView animated:YES];
		return NO;
		
		//open in safari:
		//return ![ [ UIApplication sharedApplication ] openURL: [ requestURL autorelease ] ];
	}
	
	//Open links with a protocol/scheme of 'myapp' (myapp://...);
	if ( [ [ requestURL scheme ] isEqualToString: @"app" ] 
		&& ( navigationType == UIWebViewNavigationTypeLinkClicked ) ) { 
		
		//If this is an external click, show the ProofView and load the requestURL in the provided webview
		NSLog(@"CUSTOM URL CLICKED --> %@\n\n\n", requestURL);
		UIAlertView* dialog = [[UIAlertView alloc] init];
		[dialog setDelegate:self];
		[dialog setTitle:@"Custom Control"];
		[dialog setMessage:@"You Clicked a custom control 'app://'"];
		[dialog addButtonWithTitle:@"close"];
		[dialog show];
		[dialog release];
		return NO;
		
	}
	// Auto release 
	[ requestURL release ]; 
	// If request url is something other than http or https it will open 
	// in UIWebView. You could also check for the other following 
	// protocols: tel, mailto and sms 
	return YES; 
}


@end
