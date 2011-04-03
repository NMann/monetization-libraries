//
//  plain_iOS_integrationAppDelegate.h
//  plain-iOS-integration
//
//  Created by Ryan Matsumura on 3/21/11.
//  Copyright 2011 self. All rights reserved.
//

#import <UIKit/UIKit.h>

@class plain_iOS_integrationViewController;

@interface plain_iOS_integrationAppDelegate : NSObject <UIApplicationDelegate> {
    UIWindow *window;
    plain_iOS_integrationViewController *viewController;
}

@property (nonatomic, retain) IBOutlet UIWindow *window;
@property (nonatomic, retain) IBOutlet plain_iOS_integrationViewController *viewController;

@end

