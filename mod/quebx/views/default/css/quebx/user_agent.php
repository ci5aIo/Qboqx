<?php
$element = elgg_extract('element', $vars);

Switch ($element){
    case 'experience':
        $user_agent = "<style type='text/css'>
            .tc_scrim {
              position: fixed;
              top: 0;
              left: 0;
              bottom: 0;
              right: 0;
              z-index: 100;
            }
                        
            .tc_scrim_dark {
              background: #000;
              opacity: .6;
            }
           </style>
                    
           <style type='text/css'>
            ._1JFyr__TC-BaseModal {
              position: fixed;
              display: flex;
              justify-content: center;
              z-index: 100;
              outline: none;
              top: 10vh;
              left: 0;
              bottom: 0;
              right: 0;
              max-height: 80vh;
              min-height: 230px;
            }
                        
            ._2RHv7__TC-BaseModal__children {
              width: 600px;
              max-height: inherit;
              z-index: 100;
              position: relative;
            }
           </style>

           <style type='text/css'>
            button::-moz-focus-inner {
              border:0;
              padding:0;
            }
                        
            .SMkCk__Button {
              display: inline-block;
              text-align: center;
              vertical-align: middle;
              box-sizing: border-box;
                        
              background: #fff;
              background-image: none;
              border: 1px solid transparent;
              border-radius: 2px;
              color: #333;
              cursor: pointer;
              font-weight: 400;
              line-height: 1.5;
              margin: 0 2px;
              touch-action: manipulation;
              user-select: none;
              white-space: nowrap;
                        
              transition-property: background-color, color, opacity;
              transition-duration: 300ms;
              transition-timing-function: ease-out;
                        
              font-size: 14px;
              padding: 8px 10px;
            }
                        
            .SMkCk__Button:focus {
              outline: none;
              border: 1px solid #CDE5FF;
              box-shadow: 0 0 1px 2px #83BEFF;
            }
                        
            /* short line height */
            ._1m_u1__Button--short {
              line-height: 1;
            }
                        
            /* small size */
            /* line-height is rounded differently depending on browser so be specific */
            ._3olWk__Button--small {
              font-size: 12px;
              line-height: 19px;
              padding: 4px 8px;
            }
                        
            /* large size */
            ._2hpr7__Button--large {
              font-size: 16px;
              padding: 8px 16px;
            }
                        
            /* default type */
            ._3INnV__Button--default {
              background-color: #666;
              color: white;
              text-align: center;
            }
            ._3INnV__Button--default:focus,
            ._3INnV__Button--default:hover {
              opacity: 0.9;
            }
                        
            /* Primary type */
            .QbMBD__Button--primary {
              background-color: #3676c0;
              color: white;
            }
            .QbMBD__Button--primary:focus,
            .QbMBD__Button--primary:hover {
              background-color: #305D93;
            }
                        
            /* Positive type */
            ._2OlcC__Button--positive {
              background-color: #56AD56;
              color: white;
            }
            ._2OlcC__Button--positive:focus,
            ._2OlcC__Button--positive:hover {
              background-color: #448A44;
            }
                        
            /* Warning type */
            .SSqkh__Button--warning {
              background-color: #BD2C00;
              color: white;
            }
            .SSqkh__Button--warning:focus,
            .SSqkh__Button--warning:hover {
              background-color: #972300;
            }
                        
            /* lined type */
            .VVi6C__Button--lined {
              color: #3676c0;
              border: 1px solid #ddd;
            }
            .VVi6C__Button--lined:focus,
            .VVi6C__Button--lined:hover {
              border-color: #ccc;
              background: #f5f5f5;
            }
                        
            /* Open Type */
            .ibMWB__Button--open {
              background-color: white;
              color: #666;
            }
            .ibMWB__Button--open:focus,
            .ibMWB__Button--open:hover {
              background-color: #efefef;
            }
                        
            /* Header Type */
            ._3jN8d__Button--header {
              background-color: transparent;
              color: #CADCE4;
              text-transform: uppercase;
              line-height: 34px;
              font-weight: 600;
              font-size: 11px;
              font-family: inherit;
              padding: 0 14px 0 2px;
              margin: 0;
                        
              position: relative;
              white-space: nowrap;
              overflow: hidden;
              text-overflow: ellipsis;
              display: block;
              min-height: 33px;
              max-width: 160px;
            }
                        
            ._3jN8d__Button--header:hover {
              color: #fff;
            }
                        
            ._3jN8d__Button--header:after {
              position: absolute;
              top: 43%;
              right: 2px;
              width: 0;
              height: 0;
              content: '';
              border-style: solid;
              border-width: 4px 3px 0 3px;
              border-color: rgba(255, 255, 255, 0.3) transparent transparent transparent;
            }
            /* Lined Warning Type */
            ._3lGCP__Button--lined-warning {
              color: #BD2C00;
              border: 1px solid #ddd;
            }
            ._3lGCP__Button--lined-warning:focus,
            ._3lGCP__Button--lined-warning:hover {
              border-color: #ccc;
              background: #f5f5f5;
            }
                        
            /* Disabled */
            ._3Xvsn__Button--disabled {
              cursor: not-allowed;
              text-decoration: none;
              background-color: #dfdfdf;
              color: #9f9f9f;
            }
                        
            ._3Xvsn__Button--disabled:hover {
              background-color: #dfdfdf;
              text-decoration: none;
              color: #9f9f9f;
            }
                        
            ._3RvFP__Button--full-width {
              width: 100%;
            }
            </style>

            <style type='text/css'>
            ._3qznz__AlertDialog {
              background-color: #fff;
              border-radius: 3px;
              padding: 32px;
              box-shadow: 0px 2px 19px 1px rgba(0,0,0,0.64);
              text-align: left;
              width: 328px;
              margin: 0 auto;
              color: #333;
            }
                        
            ._3OPJ8__AlertDialog__title {
                        
              text-transform: capitalize;
                        
              color: #333;
              font-size: 18px;
              font-weight: 600;
            }
                        
            ._1Rj75__AlertDialog__message {
              line-height: 21px;
              color: #666;
              font-size: 14px;
              word-wrap: break-word;
              max-height: 50vh;
              overflow: auto;
              padding: 2px;
            }
                        
            ._1Rj75__AlertDialog__message p {
              font-size: 14px;
              margin: 16px 0 0 0;
            }
                        
            ._1Rj75__AlertDialog__message strong {
              font-size: 14px;
              font-weight: 600;
            }
                        
            .Q-YPS__AlertDialog__buttons {
              text-align: right;
              margin-top: 26px;
            }
            </style>

            <style type='text/css'>
            .Avatar {
              position: relative;
              display: block;
              width: 32px;
              height: 32px;
              border-radius: 16px;
              color: #FFFFFF;
              box-sizing: border-box;
              font-size: 10px;
              line-height: 32px;
              margin: 1px 0 0 6px;
              text-align: center;
              text-transform: uppercase;
              text-overflow: clip;
            }
                        
            .Avatar--initials {
              background-color: #A1A4AD;
              border-radius: 16px;
            }
            .Avatar--header {
              width: 25px;
              height: 25px;
              display: flex;
              align-items: center;
              justify-content: center;
            }
            .Avatar__image {
              position: absolute;
              width: 32px;
              height: 32px;
              top: 0;
              left: 0;
              border-radius: 16px; /* Necessary for Chrome bug related to will-change:transform on great-x-grandparent */
            }
                        
            .Avatar__image--header {
              width: 24px;
              height: 24px;
            }
                        
            .Avatar__image--broken {
              display: none;
            }
                        
            .layouts.projector .maximized .Avatar {
              font-size: 14px;
            }
                        
            .AvatarModal {
              position: absolute;
              border-radius: 3px;
              z-index: 10;
              width: 208px;
              padding-bottom: 16px;
              margin-bottom: 8px;
              color: #222;
              box-sizing: border-box;
              background-color: #fff;
              box-shadow: 0 2px 16px 1px rgba(0, 0, 0, 0.56);
              text-transform: none;
              text-align: center;
              font-size: 14px;
              line-height: normal;
              display: flex;
              flex-direction: column;
              align-items: center;
            }
                        
            .AvatarModal__imageContainer {
              position: relative;
              width: 160px;
              height: 160px;
              margin: 8px 0;
            }
                        
            .AvatarModal__underlyingInitials, .AvatarModal__image {
              position: absolute;
              top: 0;
              left: 0;
              width: 100%;
              border-radius: 80px;
            }
                        
            .AvatarModal__personalInfoContainer {
              width: 100%;
            }
                        
            .AvatarModal__underlyingInitials {
              color: #fff;
              background-color: #A1A4AD;
              font-size: 36px;
              line-height: 160px;
              text-transform: uppercase;
            }
                        
            .AvatarModal__image--broken {
              display: none;
            }
                        
            .AvatarModal__name {
              width: 176px;
              max-height: 38px;
              overflow: hidden;
              font-weight: bold;
              padding: 0 16px;
            }
                        
            .AvatarModal__usernameContainer {
              width: 176px;
              display: flex;
              justify-content: center;
              padding: 0 16px 12px;
            }
                        
            .AvatarModal__username {
              overflow: hidden;
              text-overflow: ellipsis;
            }
                        
            .AvatarModal__initials {
              flex: 0 0 auto;
              text-transform: uppercase;
              white-space: nowrap;
            }
                        
            .AvatarModal__initials:before {
              content: '2022';
              padding-left: 6px;
              padding-right: 6px;
            }
                        
            .AvatarModal__controls {
              padding: 0 16px;
            }
                        
            .AvatarModal__controls > button {
              margin: 0;
            }
            </style>
            <style type='text/css'>
            ._34j3c__Cropper, ._34j3c__Cropper img {
              width: 384px;
              height: 384px;
              position: relative;
            }
                        
            .Ww0vX__Cropper__canvas {
              display: none;
            }
                        
            ._2_mg4__Cropper__loading {
              position: absolute;
              top: 0;
              left: 0;
              width: 100%;
              height: 100%;
              background-color: rgba(0, 0, 0, 0.5);
              color: #fff;
              display: flex;
              align-items: center;
              justify-content: center;
            }
                        
            ._2KgGi__Cropper__spinner {
              width: 48px;
              height: 48px;
            }
            </style>

            <style type='text/css'>
            @keyframes CHdgw__container-rotate {
              to {
                transform: rotate(360deg);
              }
            }
                        
            @keyframes _1_q9m__fill-unfill-rotate {
             12.5% {
                       transform: rotate(135deg);
             }
             /* 0.5 * ARCSIZE */
             25% {
                       transform: rotate(270deg);
             }
             /* 1   * ARCSIZE */
             37.5% {
                       transform: rotate(405deg);
             }
             /* 1.5 * ARCSIZE */
             50% {
                       transform: rotate(540deg);
             }
             /* 2   * ARCSIZE */
             62.5% {
                       transform: rotate(675deg);
             }
             /* 2.5 * ARCSIZE */
             75% {
                       transform: rotate(810deg);
             }
             /* 3   * ARCSIZE */
             87.5% {
                       transform: rotate(945deg);
             }
             /* 3.5 * ARCSIZE */
             to {
                       transform: rotate(1080deg);
             }
             /* 4   * ARCSIZE */
            }
                        
            @keyframes _198K2__blue-fade-in-out {
             from {
               opacity: 1;
             }
             25% {
               opacity: 1;
             }
             26% {
               opacity: 0;
             }
             89% {
               opacity: 0;
             }
             90% {
               opacity: 1;
             }
             100% {
               opacity: 1;
             }
            }
                        
            @keyframes _2fi-E__right-spin {
             from {
                       transform: rotate(-130deg);
             }
             50% {
                       transform: rotate(5deg);
             }
             to {
                       transform: rotate(-130deg);
             }
            }
                        
            @keyframes _2Zkrf__left-spin {
             from {
                       transform: rotate(130deg);
             }
             50% {
                       transform: rotate(-5deg);
             }
             to {
                       transform: rotate(130deg);
             }
            }
                        
            .w7tOS__Spinner {
              animation: CHdgw__container-rotate 1568ms linear infinite;
              display: inline-block;
              position: relative;
              width: 100%;
              height: 100%;
            }
                        
            .w7tOS__Spinner * {
              box-sizing: border-box;
            }
                        
            @keyframes CHdgw__container-rotate {
              to {
                transform: rotate(360deg);
              }
            }
                        
            ._2MtDk__Spinner__layer {
             position: absolute;
             width: 100%;
             height: 100%;
             opacity: 0;
             border-color: #26a69a;
            }
                        
            ._3YWsB__Spinner__layer--blue {
             border-color: #4285f4;
            }
                        
            /**
            * IMPORTANT NOTE ABOUT CSS ANIMATION PROPERTIES (keanulee):
            *
            * iOS Safari (tested on iOS 8.1) does not handle animation-delay very well - it doesn't
            * guarantee that the animation will start _exactly_ after that value. So we avoid using
            * animation-delay and instead set custom keyframes for each color (as redundant as it
            * seems).
            *
            * We write out each animation in full (instead of separating animation-name,
            * animation-duration, etc.) because under the polyfill, Safari does not recognize those
            * specific properties properly, treats them as -webkit-animation, and overrides the
            * other animation rules. See https://github.com/Polymer/platform/issues/53.
            */
            ._3YWsB__Spinner__layer--blue {
             /* durations: 4 * ARCTIME */
             animation: _1_q9m__fill-unfill-rotate 5332ms cubic-bezier(0.4, 0, 0.2, 1) infinite both;
            }
                        
            ._2MtDk__Spinner__layer,
            ._3YWsB__Spinner__layer--blue {
             /* durations: 4 * ARCTIME */
             opacity: 1;
             animation: _1_q9m__fill-unfill-rotate 5332ms cubic-bezier(0.4, 0, 0.2, 1) infinite both;
            }
                        
            @keyframes _1_q9m__fill-unfill-rotate {
              12.5% {
                transform: rotate(135deg);
              }
              /* 0.5 * ARCSIZE */
              25% {
                transform: rotate(270deg);
              }
              /* 1   * ARCSIZE */
              37.5% {
                transform: rotate(405deg);
              }
              /* 1.5 * ARCSIZE */
              50% {
                transform: rotate(540deg);
              }
              /* 2   * ARCSIZE */
              62.5% {
                transform: rotate(675deg);
              }
              /* 2.5 * ARCSIZE */
              75% {
                transform: rotate(810deg);
              }
              /* 3   * ARCSIZE */
              87.5% {
                transform: rotate(945deg);
              }
              /* 3.5 * ARCSIZE */
              to {
                transform: rotate(1080deg);
              }
              /* 4   * ARCSIZE */
            }
                        
            @keyframes _198K2__blue-fade-in-out {
             from {
               opacity: 1;
             }
             25% {
               opacity: 1;
             }
             26% {
               opacity: 0;
             }
             89% {
               opacity: 0;
             }
             90% {
               opacity: 1;
             }
             100% {
               opacity: 1;
             }
            }
                        
            @keyframes Oo2-0__red-fade-in-out {
             from {
               opacity: 0;
             }
             15% {
               opacity: 0;
             }
             25% {
               opacity: 1;
             }
             50% {
               opacity: 1;
             }
             51% {
               opacity: 0;
             }
            }
                        
            @keyframes _3JQhE__yellow-fade-in-out {
             from {
               opacity: 0;
             }
             40% {
               opacity: 0;
             }
             50% {
               opacity: 1;
             }
             75% {
               opacity: 1;
             }
             76% {
               opacity: 0;
             }
            }
                        
            @keyframes _2pfDY__green-fade-in-out {
              from {
                opacity: 0;
              }
              65% {
                opacity: 0;
              }
              75% {
                opacity: 1;
              }
              90% {
                opacity: 1;
              }
              100% {
                opacity: 0;
              }
            }
                        
            /**
            * Patch the gap that appear between the two adjacent div:local(.Spinner__circle-clipper) while the
            * spinner is rotating (appears on Chrome 38, Safari 7.1, and IE 11).
            */
            .FpOuE__Spinner__gap-patch {
              position: absolute;
              top: 0;
              left: 45%;
              width: 10%;
              height: 100%;
              overflow: hidden;
              border-color: inherit;
            }
                        
            .FpOuE__Spinner__gap-patch ._3DYMc__Spinner__circle {
              width: 1000%;
              left: -450%;
            }
                        
            .cNSIr__Spinner__circle-clipper {
              display: inline-block;
              position: relative;
              width: 50%;
              height: 100%;
              overflow: hidden;
              border-color: inherit;
            }
                        
            .cNSIr__Spinner__circle-clipper ._3DYMc__Spinner__circle {
              width: 200%;
              height: 100%;
              border-style: solid;
              border-color: inherit;
              border-bottom-color: transparent;
              border-radius: 50%;
              animation: none;
              position: absolute;
              top: 0;
              right: 0;
              bottom: 0;
            }
                        
            ._23AjJ__Spinner__circle-clipper--left ._3DYMc__Spinner__circle {
              left: 0;
              border-right-color: transparent;
              transform: rotate(129deg);
              animation: _2Zkrf__left-spin 1333ms cubic-bezier(0.4, 0, 0.2, 1) infinite both;
            }
                        
            ._1NuZb__Spinner__circle-clipper--right ._3DYMc__Spinner__circle {
              left: -100%;
              border-left-color: transparent;
              transform: rotate(-129deg);
              animation: _2fi-E__right-spin 1333ms cubic-bezier(0.4, 0, 0.2, 1) infinite both;
            }
                        
            @keyframes _2Zkrf__left-spin {
              from {
               transform: rotate(130deg);
              }
              50% {
               transform: rotate(-5deg);
              }
              to {
               transform: rotate(130deg);
              }
            }
                        
            @keyframes _2fi-E__right-spin {
              from {
               transform: rotate(-130deg);
              }
              50% {
               transform: rotate(5deg);
              }
              to {
               transform: rotate(-130deg);
              }
            }
                        
            .cNSIr__Spinner__circle-clipper ._3DYMc__Spinner__circle {
              border-width: 3px;
            }
                        
            ._3DYMc__Spinner__circle {
              outline: 1px solid transparent; /* require to remove jagged edges in firefox */
            }
                        
            ._2avLF__Spinner__layer---blue {
               border-color: #4078C0;
            }
            </style>

            <style type='text/css'>
            ._3A65L__ProfilePhoto__imageContainer {
              display: flex;
              justify-content: center;
              align-items: center;
              margin-bottom: 24px;
              margin-top: 24px;
                        
              height: 384px;
            }
            .yp3jX__ProfilePhoto__imageContainer-blank:hover {
              cursor: pointer;
              opacity: 0.5;
            }
                        
            ._169ii__ProfilePhoto__image {
              height: 384px;
              width: 384px;
              border-radius: 50%;
            }
                        
            div ._2mJG0__ProfilePhoto__modal {
              width: 736px;
              height: 80%;
              max-height: 700px;
            }
                        
            ._2mJG0__ProfilePhoto__modal [data-aid='modal-content'] {
              height: 100%;
              width: 736px;
            }
                        
            ._110i3__ProfilePhoto__choosePhotoContainer {
              width: 100%;
              display: flex;
              justify-content: center;
            }
                        
            ._1lMYm__ProfilePhoto__choosePhoto {
              text-transform: none;
            }
                        
            .PBOWS__ProfilePhoto__imageErrorPlaceholder{
              background-image: url(//assets.pivotaltracker.com/next/assets/next/ce606fb4-tippy_with_bad_file.svg);
              background-position: center center;
              background-repeat: no-repeat;
              background-color: #E4E5E8;
              border: solid 1px #DFE0E2;
              width: 384px;
              height: 384px;
            }
                        
            ._1vnC2__ProfilePhoto__cameraPlaceholder {
              display: flex;
              justify-content: center;
              align-items: center;
                        
              width: 385px;
              height: 384px;
              border: solid 1px #BBB;
              opacity: 0.5;
            }
                        
            ._1vnC2__ProfilePhoto__cameraPlaceholder:hover {
              opacity: 1;
              cursor: pointer;
            }
                        
            ._3_XC6__FileModalError {
              background-color: #FFEFED;
              border-bottom: solid 1px #FBD3CA;
              margin-bottom: 20px;
              padding: 3px;
              font-size: 14px;
              color: #4a4a4a;
              font-weight: 300;
            }
                        
            ._2CCZk__FileModalError__container {
              border-left: 4px solid #ED6347;
              display: flex;
              align-items: center;
              white-space: initial;
              margin-left: 2px;
              padding: 14px 16px;
            }
                        
            ._1AAiK__FileModalError__icon {
              flex: none;
              height: 32px;
              width: 32px;
              border-radius: 15px;
              margin-right: 12px;
              background-repeat: no-repeat;
              background-position: center center;
              background-color: #FBD3CB;
              background-image: url(//assets.pivotaltracker.com/next/assets/next/67c37afd-critical-icon-svgo.svg);
            }
                        
            ._2fryr__FileModalError__message {
              display: flex;
              flex-direction: column;
            }
            </style>

            <style type='text/css'>
            /*!
             * Cropper.js v1.0.0-rc.2
             * https://github.com/fengyuanchen/cropperjs
             *
             * Copyright (c) 2017 Fengyuan Chen
             * Released under the MIT license
             *
             * Date: 2017-05-30T05:02:48.005Z
             */
                        
            .cropper-container {
              font-size: 0;
              line-height: 0;
                        
              position: relative;
                        
              -webkit-user-select: none;
                        
                 -moz-user-select: none;
                        
                  -ms-user-select: none;
                        
                      user-select: none;
                        
              direction: ltr;
              -ms-touch-action: none;
                  touch-action: none
            }
                        
            .cropper-container img {
              /* Avoid margin top issue (Occur only when margin-top <= -height) */
              display: block;
              min-width: 0 !important;
              max-width: none !important;
              min-height: 0 !important;
              max-height: none !important;
              width: 100%;
              height: 100%;
              image-orientation: 0deg
            }
                        
            .cropper-wrap-box,
            .cropper-canvas,
            .cropper-drag-box,
            .cropper-crop-box,
            .cropper-modal {
              position: absolute;
              top: 0;
              right: 0;
              bottom: 0;
              left: 0;
            }
                        
            .cropper-wrap-box {
              overflow: hidden;
            }
                        
            .cropper-drag-box {
              opacity: 0;
              background-color: #fff;
            }
                        
            .cropper-modal {
              opacity: .5;
              background-color: #000;
            }
                        
            .cropper-view-box {
              display: block;
              overflow: hidden;
                        
              width: 100%;
              height: 100%;
                        
              outline: 1px solid #39f;
              outline-color: rgba(51, 153, 255, 0.75);
              border-radius: 50%;
            }
                        
            .cropper-dashed {
              position: absolute;
                        
              display: block;
                        
              opacity: .5;
              border: 0 dashed #eee
            }
                        
            .cropper-dashed.dashed-h {
              top: 33.33333%;
              left: 0;
              width: 100%;
              height: 33.33333%;
              border-top-width: 1px;
              border-bottom-width: 1px
            }
                        
            .cropper-dashed.dashed-v {
              top: 0;
              left: 33.33333%;
              width: 33.33333%;
              height: 100%;
              border-right-width: 1px;
              border-left-width: 1px
            }
                        
            .cropper-center {
              position: absolute;
              top: 50%;
              left: 50%;
                        
              display: block;
                        
              width: 0;
              height: 0;
                        
              opacity: .75
            }
                        
            .cropper-center:before,
              .cropper-center:after {
              position: absolute;
              display: block;
              content: ' ';
              background-color: #eee
            }
                        
            .cropper-center:before {
              top: 0;
              left: -3px;
              width: 7px;
              height: 1px
            }
                        
            .cropper-center:after {
              top: -3px;
              left: 0;
              width: 1px;
              height: 7px
            }
                        
            .cropper-face,
            .cropper-line,
            .cropper-point {
              position: absolute;
                        
              display: block;
                        
              width: 100%;
              height: 100%;
                        
              opacity: .1;
            }
                        
            .cropper-face {
              top: 0;
              left: 0;
                        
              background-color: #fff;
            }
                        
            .cropper-line {
              background-color: #39f
            }
                        
            .cropper-line.line-e {
              top: 0;
              right: -3px;
              width: 5px;
              cursor: e-resize
            }
                        
            .cropper-line.line-n {
              top: -3px;
              left: 0;
              height: 5px;
              cursor: n-resize
            }
                        
            .cropper-line.line-w {
              top: 0;
              left: -3px;
              width: 5px;
              cursor: w-resize
            }
                        
            .cropper-line.line-s {
              bottom: -3px;
              left: 0;
              height: 5px;
              cursor: s-resize
            }
                        
            .cropper-point {
              width: 5px;
              height: 5px;
                        
              opacity: .75;
              background-color: #39f
            }
                        
            .cropper-point.point-e {
              top: 50%;
              right: -3px;
              margin-top: -3px;
              cursor: e-resize
            }
                        
            .cropper-point.point-n {
              top: -3px;
              left: 50%;
              margin-left: -3px;
              cursor: n-resize
            }
                        
            .cropper-point.point-w {
              top: 50%;
              left: -3px;
              margin-top: -3px;
              cursor: w-resize
            }
                        
            .cropper-point.point-s {
              bottom: -3px;
              left: 50%;
              margin-left: -3px;
              cursor: s-resize
            }
                        
            .cropper-point.point-ne {
              top: -3px;
              right: -3px;
              cursor: ne-resize
            }
                        
            .cropper-point.point-nw {
              top: -3px;
              left: -3px;
              cursor: nw-resize
            }
                        
            .cropper-point.point-sw {
              bottom: -3px;
              left: -3px;
              cursor: sw-resize
            }
                        
            .cropper-point.point-se {
              right: -3px;
              bottom: -3px;
              width: 20px;
              height: 20px;
              cursor: se-resize;
              opacity: 1
            }
                        
            @media (min-width: 768px) {
                        
              .cropper-point.point-se {
                width: 15px;
                height: 15px
              }
            }
                        
            @media (min-width: 992px) {
                        
              .cropper-point.point-se {
                width: 10px;
                height: 10px
              }
            }
                        
            @media (min-width: 1200px) {
                        
              .cropper-point.point-se {
                width: 5px;
                height: 5px;
                opacity: .75
              }
            }
                        
            .cropper-point.point-se:before {
              position: absolute;
              right: -50%;
              bottom: -50%;
              display: block;
              width: 200%;
              height: 200%;
              content: ' ';
              opacity: 0;
              background-color: #39f
            }
                        
            .cropper-invisible {
              opacity: 0;
            }
                        
            .cropper-bg {
              background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQAQMAAAAlPW0iAAAAA3NCSVQICAjb4U/gAAAABlBMVEXMzMz////TjRV2AAAACXBIWXMAAArrAAAK6wGCiw1aAAAAHHRFWHRTb2Z0d2FyZQBBZG9iZSBGaXJld29ya3MgQ1M26LyyjAAAABFJREFUCJlj+M/AgBVhF/0PAH6/D/HkDxOGAAAAAElFTkSuQmCC');
            }
                        
            .cropper-hide {
              position: absolute;
                        
              display: block;
                        
              width: 0;
              height: 0;
            }
                        
            .cropper-hidden {
              display: none !important;
            }
                        
            .cropper-move {
              cursor: move;
            }
                        
            .cropper-crop {
              cursor: crosshair;
            }
                        
            .cropper-disabled .cropper-drag-box,
            .cropper-disabled .cropper-face,
            .cropper-disabled .cropper-line,
            .cropper-disabled .cropper-point {
              cursor: not-allowed;
            }
                        
            </style>

            <style type='text/css'>
            ._2AG7P__ConfirmationDialog {
              background-color: #fff;
              border-radius: 3px;
              padding: 32px;
              box-shadow: 0px 2px 19px 1px rgba(0,0,0,0.64);
              text-align: left;
              width: 328px;
              margin: 0 auto;
              color: #333;
            }
                        
            ._165Vh__ConfirmationDialog__title {
                        
              text-transform: capitalize;
                        
              color: #333;
              font-size: 18px;
              font-weight: 600;
            }
                        
            ._1DwNk__ConfirmationDialog__message {
              line-height: 21px;
              color: #666;
              font-size: 14px;
              word-wrap: break-word;
              max-height: 50vh;
              overflow: auto;
              padding: 2px;
            }
                        
            ._1DwNk__ConfirmationDialog__message p {
              font-size: 14px;
              margin: 16px 0 0 0;
            }
                        
            ._1DwNk__ConfirmationDialog__message strong {
              font-size: 14px;
              font-weight: 600;
            }
                        
            ._23Nhj__ConfirmationDialog__buttons {
              margin-top: 32px;
              text-align: right;
              margin-top: 26px;
            }
            </style>

            <style type='text/css'>
            .Dropdown {
              display: flex;
            }
                        
            .Dropdown__content {
              color: #000;
              font-family: open-sans, Helvetica, Arial, EmojiFontFace, sans-serif;
              font-size: 14px;
              font-weight: 400;
              position: relative;
              width: auto;
            }
                        
            .Dropdown__button {
              cursor: pointer;
              position: relative;
            }
                        
            .Dropdown__button:disabled {
              cursor: not-allowed;
            }
                        
            /* options */
                        
            .Dropdown__options {
              background-color: #FFF;
              border-radius: 2px;
              border: solid 1px #DDDDDD;
              box-shadow: 0 4px 6px rgba(0, 0, 0, 0.14);
              display: block;
              right: 0;
              padding-bottom: 4px;
              padding-top: 6px;
              position: absolute;
              white-space: nowrap;
              width: auto;
              z-index: 101;
              margin-top: 4px;
              line-height: 30px;
              font-size: 14px;
            }
                        
            .Dropdown__options--small{
              width: 200px;
              font-size: 12px;
              line-height: 24px;
            }
                        
            .Dropdown--left .Dropdown__options {
              left: 0;
              right: auto;
            }
                        
            .Dropdown__option:hover{
              background-color: #E6E6E6;
            }
                        
            .Dropdown__option {
              display: block;
              padding: 2px 16px;
              text-align: left;
              width: 100%;
              box-sizing: border-box;
            }
                        
                        
            /* specific option styles */
                        
                        
            .Dropdown__horizontal-rule {
              border-top: solid 2px #eee;
              margin: 7px 0;
              height: 1px
            }
                        
            .Dropdown__option--link {
              color: inherit;
              text-decoration: none;
            }
                        
            .Dropdown__option--link:hover {
              text-decoration: none;
            }
                        
            .Dropdown__option--button,
            .Dropdown__option--button-danger,
            .Dropdown__option--button-disabled {
              background: none;
              border: none;
              cursor: pointer;
              font-size: inherit;
              line-height: inherit;
            }
                        
            .Dropdown__option--button-danger {
              color: #C8583E;
            }
                        
            .Dropdown__option--button-disabled {
              font-weight: 200;
              cursor: not-allowed;
              color: #AEAEAE;
            }
                        
            .Dropdown__option--label:hover,
            .Dropdown__option--label-with-border:hover {
              background: none;
              cursor: default;
            }
                        
            .Dropdown__option--label {
              color: #AEAEAE;
            }
                        
            .Dropdown__option--label-with-border {
              font-weight: 600;
              white-space: normal;
              line-height: 16px;
              font-size: 12px;
              border-left: 4px solid #F7C204;
              margin-left: 2px;
              padding: 4px 16px;
            }
            </style>

            <style type='text/css'>
            .tc_projects_menu_dashboard:before {
              width: 12px;
              height: 12px;
              background: url(//assets.pivotaltracker.com/next/assets/next/2aac1116-home.svg) no-repeat;
            }
                        
            .tc_create_project a:after, .tc_create_workspace a:after {
              width: 11px;
              height: 11px;
              background: url(//assets.pivotaltracker.com/next/assets/next/a1e49a9f-plus.svg) no-repeat;
            }
                        
            .tc_menu_header_workspaces:before {
              width: 9px;
              height: 9px;
              background: url(//assets.pivotaltracker.com/next/assets/next/4213cbec-windows.svg) no-repeat;
            }
                        
            .tc_menu_header_projects:before {
              width: 12px;
              height: 7px;
              background: url(//assets.pivotaltracker.com/next/assets/next/d310141b-velocity.svg) no-repeat;
            }
                        
            .tc_projects_menu_show_all:after {
              width: 6px;
              height: 8px;
              background: url(//assets.pivotaltracker.com/next/assets/next/a9552480-greater_than.svg) no-repeat;
            }
                        
            .tc_select_dropdown_fetching:after {
              width: 16px;
              height: 16px;
              background: url('data:image/gif;base64,R0lGODlhEAAQAPIAAOvp4wAAALOxrT08OgAAAFpZV3h3dIeFgiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==') no-repeat;
            }
                        
            button.tc_button {
              cursor: pointer;
              border: none;
              transition: opacity 0.2s;
            }
            button.tc_button:hover {
              opacity: 0.90;
            }
                        
            button.tc_button_cancel {
              background: none;
              color: #888;
              margin-right: 16px;
            }
                        
            button.tc_button_submit {
              border-radius: 2px;
              background-color: #306BAA;
              color: #fff;
              padding: 8px;
            }
                        
            .tc_modal_v1_container {
              position: fixed;
              top: 0;
              left: 0;
              display: flex;
              flex-direction: column;
              justify-content: center;
              align-items: center;
              width: 100%;
              height: 100%;
              z-index: 100;
              overflow: hidden;
            }
                        
            .tc_modal_v0 {
              position: fixed;
              top: 30%;
              left: 50%;
              z-index: 100;
            }
                        
            .tc_modal_v0 .tc_modal_content header {
              color: #fff;
              padding: 10px 15px;
              background-color: #908674;
              border-top-left-radius: 4px;
              border-top-right-radius: 4px;
            }
                        
            .tc_modal_v0 .tc_modal_content header h1 {
              text-transform: uppercase;
              line-height: 14px;
              font-size: 11px;
              font-weight: 600;
              letter-spacing: 1px;
              text-rendering: optimizeLegibility;
            }
                        
            .tc_modal_v0 .tc_modal_content {
              user-select: text;
              text-rendering: optimizeLegibility;
              box-shadow: 0 2px 5px rgba(0, 0, 0, 0.4);
              z-index: 100;
              overflow: visible;
              border-radius: 4px;
              color: #000;
              background-color: #f3f3f3;
              position: relative;
            }
                        
            .tc_modal_v1 {
              position: initial;
              top: initial;
              left: initial;
              display: flex;
              max-height: 550px;
              z-index: 100;
            }
                        
            .tc_modal_v1 .tc_modal_content header {
              color: #fff;
              padding: 12px 20px;
              background-color: #306FA7;
              box-shadow: 0 2px 5px rgba(0, 0, 0, 0.4);
              flex-shrink: 0;
            }
                        
            .tc_modal_v1 .tc_modal_content form {
              height: 100%;
              display: flex;
              flex-direction: column;
            }
                        
            .tc_modal_v1 .tc_modal_content footer {
              flex-shrink: 0;
              bottom: 0;
              box-sizing: border-box;
              width: 100%;
              z-index: 100;
            }
                        
            .tc_modal_v1 .tc_modal_content header h1 {
              line-height: 36px;
              font-size: 22px;
              font-weight: normal;
              letter-spacing: -0.02em;
              text-rendering: optimizeLegibility;
            }
                        
            .tc_modal_v1 .tc_modal_content {
              user-select: text;
              text-rendering: optimizeLegibility;
              box-shadow: 0 2px 5px rgba(0, 0, 0, 0.4);
              z-index: 100;
              overflow: hidden;
              height: 100%;
              border-radius: 4px;
              color: #000;
              background-color: #f6f6f6;
              position: relative;
            }
                        
            .tc_callout {
              font-weight: bold;
            }
                        
            .tc_warning {
              background-color: #FEFFBC;
              border-color: #A4A871;
            }
                        
            .tc_new_account_cancellation {
              font-weight: normal;
              line-height: 35px;
              position: absolute;
              left: 275px;
              top: 17px;
              font-size: 11px;
              white-space: nowrap;
            }
                        
            .tc_link {
              text-decoration: none;
              color: #306BAA;
            }
                        
            .tc_link:hover {
              text-decoration: underline;
            }
                        
            .tc_form_label {
              color: #555;
              line-height: 20px;
              font-size: 12px;
              font-weight: 600;
              display: block;
            }
                        
            .tc_form_checkbox {
              font-size: 11px;
              color: #666;
              font-weight: 400;
            }
                        
            input[type=text].tc-form__input {
              display: block;
              border: 1px solid #ccc;
              border-radius: 3px;
              box-sizing: border-box;
              color: #333;
              font-family: 'Open Sans', open-sans, EmojiFontFace, sans-serif;
              font-size: 14px;
              line-height: 14px;
              padding: 8px 16px;
              width: 100%;
            }
                        
            input[type=text].tc-form__input--error {
              border: 1px solid #DD7270;
              background-color: #FFEEEE;
            }
                        
            input[type=text].tc-form__input:disabled {
              display: none;
            }
                        
            input[type=text].tc-form__input.sample {
              display: inherit;
              background-color: #F3F3F3;
            }
                        
            .tc_form_select {
              user-select: none;
              border-radius: 3px;
              display: block;
              width: 263px;
              padding: 0 22px 0 15px;
              box-sizing: border-box;
              background-color: #fff;
              border: 1px solid #ccc;
              height: 30px;
              line-height: 30px;
              color: #555;
              font-size: 11px;
              cursor: pointer;
              font-weight: normal;
              text-overflow: ellipsis;
              white-space: nowrap;
              overflow: hidden;
            }
                        
            .tc_form_select:after {
              background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAMCAYAAABiDJ37AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEgAACxIB0t1+/AAAABx0RVh0U29mdHdhcmUAQWRvYmUgRmlyZXdvcmtzIENTNui8sowAAAAVdEVYdENyZWF0aW9uIFRpbWUAOC8xMy8xNBsjHysAAAEZSURBVCiRndKxSgNBEMbx/x0YECSNPoKxSy0hCFZa2GgdAyEIX5PWB0htGZsBSWXq2FhoGRGS2k58BRsRhBMWC0c41lsTnO525vvt3HEZXmZ2CFwAHUlPrFBm1gQmwLmkO4DcG/vAFGgCczPrrYD1gLlnpm6QmdkucA/Uo8wYGEj6iKB14BLoR/NvwEEOjCowPLAws0YJawCLCgw3RpmZbQE3QDvxdu8lYAxsJOYegePMb675cCcxvKwmQF9SkQNIKkIIXWD4D2wYQuhKKgCyuGtmp77t2hLo07e6Lh/+Ah3d4/s32kxgr8CJpIe4UQk6ug3cAjtR6xk4kvRSlctToAdawKx0PANaKezPDUub1oArfzz7+fip+gJm9lxEf5s4QwAAAABJRU5ErkJggg==') no-repeat;
              background-size: 10px 6px;
              width: 10px;
              height: 6px;
              content: '';
              left: 240px;
              top: 33px;
              position: absolute;
            }
                        
            .tc_select_dropdown {
              position: absolute;
              display: block;
              width: 263px;
              box-sizing: border-box;
              background-color: #fff;
              border: 1px solid #ccc;
              line-height: 25px;
              color: #555;
              font-size: 11px;
              padding: 3px;
              border-radius: 3px;
              max-height: 165px;
              overflow-y: auto;
              z-index: 100;
            }
                        
            .tc_select_dropdown_fetching {
              min-height: 110px;
            }
                        
            .tc_select_dropdown_fetching:after {
              content: '';
              left: 123px;
              top: 62px;
              position: absolute;
            }
                        
            .tc_select_option {
              font-weight: normal;
              padding: 0 11px 0 15px;
              cursor: pointer;
            }
                        
            .tc_select_option:hover {
              background-color: #EEF0F1;
            }
                        
            .tc_select_account_name {
              width: 74%;
              overflow: hidden;
              display: inline-block;
              white-space: nowrap;
              text-overflow: ellipsis;
            }
                        
            .tc_select_account_owner {
              overflow: hidden;
              width: 24%;
              color: #B2B2B2;
              float: right;
              display: inline-block;
              white-space: nowrap;
              text-overflow: ellipsis;
              text-align: right;
            }
                        
            .tc_select_option_separator {
              display: block;
              height: 1px;
              border: 0;
              border-top: 1px solid #DDD;
              margin: 3px 0;
              padding: 0;
            }
                        
            .tc_select_create_account:after {
              background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEgAACxIB0t1+/AAAABx0RVh0U29mdHdhcmUAQWRvYmUgRmlyZXdvcmtzIENTNui8sowAAAAVdEVYdENyZWF0aW9uIFRpbWUAOC8xMy8xNBsjHysAAAA8SURBVDiNY/z//z8DPjBr1iwUBWlpaYz41DPhNY0MMGrgSDCQcebMmfgTIolg8HuZ+mE4mpdHDRwEBgIAtTcQ6vckxKcAAAAASUVORK5CYII=') no-repeat;
              background-size: 10px 10px;
              width: 10px;
              height: 10px;
              content: '';
              position: absolute;
              right: 15px;
              top: 11px;
            }
                        
            .tc_form_group {
              padding-bottom: 10px;
              position: relative;
              width: 275px;
            }
                        
            .tc_form_disabled {
              pointer-events: none;
            }
                        
            .tc_form_disabled .tc_form_select {
              background: transparent;
            }
                        
            .tc_tooltip_link {
              position: relative;
              color: #306BAA;
              white-space: nowrap;
            }
                        
            .tc_tooltip {
              position: absolute;
              color: #000;
              background: #ffffff;
              border-radius: 3px;
              margin-top: 5px;
              white-space: nowrap;
              width: 125px;
              left: -50px;
              top: 44px;
              z-index: 101;
            }
                        
            .tc_tooltip:before {
              content: '';
              position: relative;
              margin-left: 50px;
              top: -30px;
              background: white;
              display: block;
              width: 24px;
              height: 24px;
              box-shadow: 1px -1px 5px rgba(0, 0, 0, 0.4);
              transform: rotate(45deg);
            }
                        
            .tc_tooltip_content {
              background: white;
              margin-top: -50px;
              padding: 8px;
              box-shadow: 0 3px 5px rgba(0, 0, 0, 0.4);
              border-radius: 3px;
              transform: rotate(0deg);
            }
                        
            .tc_tooltip_content_item {
              overflow: hidden;
              text-overflow: ellipsis;
            }
                        
            .tc_error {
              line-height: 18px;
              font-size: 12px;
              padding-bottom: 16px;
            }
                        
            .tc_error_highlight {
              color: #BA0400;
            }
            .tc_error_highlight .tc_callout {
              color: #000;
            }
                        
            .introductions_page #main {
              padding: 0px;
              height: 100%;
              background-color: #eee;
            }
                        
            .tc_menu {
              position: absolute;
              background: #FFF;
              z-index: 100;
              box-shadow: 0 2px 5px rgba(0, 0, 0, 0.4);
            }
            .tc_menu.tc_justify_left {
              left: 0;
            }
                        
            .tc_page_header {
              text-rendering: optimizeLegibility;
              background-color: #256188;
              height: 34px;
              line-height: 34px;
              color: #fff;
              position: relative;
              padding: 0 5px 0 0;
              border-bottom: 1px solid #212121;
              min-width: 1000px;
            }
                        
            .tc_page_header.tc_page_header_non_prod_env {
              background-color: #AD56C6;
            }
                        
            .tc_page_header_version-ia {
              background-color: #3E7293;
            }
                        
            .tc_page_header.tc_page_header-expanded {
              height: 60px;
            }
                        
            .tc_page_header > ul > li {
              list-style: none;
              float: left;
            }
            .tc_page_header > ul > li > a {
              user-select: none;
            }
            .tc_page_header > ul > li a {
              cursor: pointer;
            }
            .tc_page_header > ul > li.tc_pull_right {
              float: right;
            }
            .tc_page_header > ul > li.tc_extra_wide {
              margin-left: 20px;
            }
            .tc_page_header > ul > li.tc_wide {
              margin-left: 18px;
            }
                        
            .tc_pull_right {
              float: right;
            }
                        
            .tc_notification_bell_fix {
              margin-right: -20px;
              padding-top: 2px;
            }
                        
            .tc_header_logo {
              width: 20px;
              height: 20px;
              margin-right: 10px;
              margin-left: 10px;
              display: block;
            }
            .tc_header_logo:before {
              background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEgAACxIB0t1+/AAAABR0RVh0Q3JlYXRpb24gVGltZQAzLzEvMTT2AEHAAAAAHHRFWHRTb2Z0d2FyZQBBZG9iZSBGaXJld29ya3MgQ1M26LyyjAAABDtJREFUWIXVmTtyG0cQhr9GgYEYkKwCAyQoQg7kQIGpwIkSUuUDUD6ByBOYPIGhE4g8gaAb4AYGEycKDAcKrECCCwkCokwisAOoqh1MLzjoncFyWSoX/SeLnee//ZrugXAPqOom0ALawCawlRk6A26AmYhM77OX1CTWATpGri4WwBT4KCJ/33XSnQiqagt4Bjy6B7EUJsAHEVlUDVxLUFU3gCfAN1+JWIwFMKpSfZagkXtO3r4A5sCVPb3adm1uC9hYs8YnEflQi6Cqbhm51MK1bUlV2wQt5Gx3IiKjOxE0yf2QITclqKVkO6raBboiMlxDdJ0tJ0k2EuRSkivs5b0np6o7qtoDPgO/qOrLHEERmQGXBCfx6Kjqt6U5brOnlB1iAfwqInM/WVWPgXNgO2r+U0S6OZIVewFcxns1ogmtmuT2gbeOHMCeSXQtzDFSknwWv8QqLomXEKtK5GyDETDI7H+qqjtxg6oOVPU0scbMzd2yA+GWoEnPe9hURFJfGOMs075NUH1B7hg4At6o6khVD6Ox7wmairEUViHBlGqzsamAiIyB15nuV6q6b5I8j9q/IzjTQFW75nQf3dxHhRSb5rltN2BaxDhVHa7hOAZOgZ+AnUT/jvV7O4Ug0Z79nhBOrDh6tIFJM0EOVr/oYA3BA2BIUPVb1/eO8AE/Z+a+K+KeiCxUdcKqJtsQVOxtb14n2yBIYQDEQfba2s8T44v+U9dWsndVbTUoi/+qBjmAPdvsjCDNa+AC6BLUmMKFiFzHDRYtvLPsNiknA8mwUoFDEekZQWCt7Y5tbApzVjXabCQG1VEvBImljrd+ZnwuNEHIvmNsN+9A4LCi/8aryzAAXrm2sYjkgjvAF99QSVBELovflrH03ZARZYMvUIqRLkhf59KsOxN06FMOO/uq2ktI8Rh4U7HeJasaKvFJ2eBmaiX78lRM3MbZoEm6V0EuBR9RbpoEz4k9OZfi9zPtYxHpq+qY4DBjEXmpqn8lNqyC3/tLg7Ln7PpZloXsZRY9sfRqj3DOHtn4kzrMrMzwifJVg3S6s1SzHfa9zLpD0k7Ss/ZhDY4d3yAisyahzvB4wu3R1SOvqhPKGTX23rN+f0bH+B2WpYYnOAVo2kE9ZTVp6KhqUbUNCEdW1y1wISJjVU3FQAgZzrmIvFhDcLkfZfVO4daLPyUmPQUQkaGIPCZIpCBTJAO4do9+FbPociDGP0Wy3DASM8q22I5TbxF5DTy2Tc+KuGfPXNJ64AJzCt9Tlt4fy32jL2kRSs4Y2aLJQ1U/UzYDWFPlWeHlbW8en17LQG1S9KreAJ5bCKhCKqwMyZzlVnaWPBf4LX5ZOUmsFPTSKkimFovnDrmt8sbAjyLywuqWmNiGSS5VE5eqyK929WFzu8CRiFxk+mtfffz/Lo+iRR/u9Zsj+TAvMGM82Ctgjwd7ie7xX/4N8S/dctnIjc1oEAAAAABJRU5ErkJggg==') no-repeat;
              background-size: 20px 20px;
              width: 20px;
              height: 20px;
              content: '';
              position: absolute;
              top: 7px;
            }
                        
            a.tc_header_item_name {
              padding-top: 1px;
            }
                        
            a.tc_header_item_name, a.tc_dropdown_name {
              font-weight: 600;
              font-size: 11px;
              text-transform: uppercase;
              text-decoration: none;
              color: #fff;
              display: block;
            }
                        
            .tc_page_header_version-ia a.tc_header_item_name,
            .tc_page_header_version-ia a.tc_dropdown_name {
              color: #CADCE4;
              transition: color .05s ease-in;
              -moz-transition: color .05s ease-in;
              -webkit-transition: color .05s ease-in;
              -o-transition: color .05s ease-in;
            }
                        
            .tc_page_header_version-ia a.tc_header_item_name:hover,
            .tc_page_header_version-ia a.tc_dropdown_name:hover {
              color: #fff;
            }
                        
            .tc_projects_menu_arrow {
              position: relative;
            }
            .tc_projects_menu_arrow:before {
              position: absolute;
              top: -5px;
              left: 10px;
              width: 0;
              height: 0;
              content: '';
              border-style: solid;
              border-width: 0 8px 8px 8px;
              border-color: transparent transparent #908674 transparent;
              z-index: 101;
            }
                        
            .tc_context_name {
              white-space: nowrap;
              overflow: hidden;
              text-overflow: ellipsis;
              display: block;
              max-width: 300px;
              position: relative;
              padding-right: 7px;
              font-size: 16px;
              text-transform: capitalize;
              font-weight: normal;
            }
                        
            a.tc_context_name {
              text-decoration: none;
            }
                        
            a.tc_context_name:hover {
              text-decoration: none;
            }
                        
            .tc_profile_dropdown {
              margin-right: 8px;
            }
                        
            .tc_projects_dropdown_link {
              color: #fff;
              font-weight: normal;
            }
                        
            .tc_projects_dropdown_link:after {
              position: absolute;
              top: 17px;
              right: 0;
              width: 0;
              height: 0;
              content: '';
              border-style: solid;
              border-width: 4px 3px 0 3px;
              border-color: rgba(255, 255, 255, 0.4) transparent transparent transparent;
            }
                        
            .tc_projects_menu {
              position: absolute;
              top: 35px;
              z-index: 100;
              width: auto;
              box-shadow: 0 2px 5px rgba(0, 0, 0, 0.4);
              border-radius: 2px;
              overflow: hidden;
            }
            .tc_projects_menu a {
              transition: background-color 0.1s ease;
            }
            .tc_projects_menu:before {
              position: absolute;
              top: -5px;
              left: 10px;
              width: 0;
              height: 0;
              content: '';
              border-style: solid;
              border-width: 0 8px 8px 8px;
              border-color: transparent transparent #908674 transparent;
            }
                        
            .tc_projects_menu_list, .tc_workspaces_menu_list {
              padding: 4px 0;
            }
                        
            .tc_projects_menu_list {
              height: 390px;
              width: 220px;
              background-color: #F3F3F3;
              position: relative;
              float: left;
            }
            .tc_projects_menu_list:last-child {
              margin-left: 2px;
            }
            .tc_projects_menu_list a:hover {
              background-color: #E3E1DC;
            }
                        
            .tc_workspaces_menu_list {
              height: 390px;
              width: 220px;
              position: relative;
              float: left;
            }
            .tc_workspaces_menu_list a:hover {
              background: #E3E1DC;
            }
                        
            .tc_menu_header {
              height: 32px;
              background-color: #908674;
              line-height: 32px;
              font-size: 12px;
              font-weight: 600;
              text-transform: uppercase;
              padding-left: 33px;
              position: relative;
            }
                        
            .tc_projects_menu_list a, .tc_workspaces_menu_list a {
              display: block;
              margin: 0 3px;
              font-size: 12px;
              font-weight: normal;
              line-height: 30px;
              text-decoration: none;
              padding: 0 10px;
              color: #000;
              position: relative;
              white-space: nowrap;
            }
                        
            .tc_projects_menu_list .raw_project_name {
              display: block;
              width: 194px;
              white-space: nowrap;
              overflow: hidden;
              text-overflow: ellipsis;
            }
            .tc_projects_menu_list .raw_project_name.public {
              width: 153px;
              position: relative;
              float: left;
            }
                        
            .tc_projects_menu_show_all:after {
              content: '';
              position: absolute;
              top: 50%;
              margin: -4px 0 0 7px;
            }
                        
            .tc_menu .tc_projects_menu_callout a {
              font-size: 12px;
              color: #7C725E;
              font-weight: bold;
            }
                        
            .tc_create_project a, .tc_create_workspace a {
              position: relative;
            }
            .tc_create_project a:after, .tc_create_workspace a:after {
              content: '';
              right: 7px;
              top: 15px;
              margin-top: -6px;
              position: absolute;
            }
                        
            .tc_projects_menu_list_group {
              float: left;
            }
                        
            .tc_menu_header_projects:before {
              content: '';
              left: 15px;
              top: 50%;
              margin-top: -4px;
              position: absolute;
            }
                        
            .tc_menu_header_workspaces:before {
              content: '';
              left: 15px;
              top: 50%;
              margin-top: -5px;
              position: absolute;
            }
                        
            .tc_projects_menu_footer {
              background-color: white;
              border-top: 1px solid #dadada;
              position: relative;
              clear: both;
              text-decoration: none;
              color: #7B725E;
              font-weight: bold;
              font-size: 12px;
              display: block;
            }
            .tc_projects_menu_footer:hover {
              background: #E3E1DC;
            }
                        
            .tc_projects_menu_dashboard {
              margin: 0 auto;
              display: block;
              position: relative;
              width: 81px;
              text-align: right;
            }
            .tc_projects_menu_dashboard:before {
              content: '';
              position: absolute;
              left: 0;
              margin-top: -7px;
              top: 50%;
            }
                        
            .tc_dropdown {
              position: relative;
            }
                        
            .tc_hidden {
              display: none;
            }
                        
            .tc_tracker_header .has_updates .tc_recent_updates:after,
            .tc_tracker_header .has_updates .tc_dropdown_name:before {
                background-color: #E26648;
                margin-left: 10px;
                color: #fff;
                content: 'NEW';
                font-size: 9px;
                padding: 0 4px 0 3px;
                border-radius: 2px;
            }
                        
            .tc_tracker_header .has_updates .tc_dropdown_name:before {
              background-color: #E26648;
              line-height: 1.6;
              position: absolute;
              right: -11px;
              top: 4px;
            }
                        
            .tc_tracker_header .has_updates .tc_dropdown_name {
              overflow: visible;
            }
                        
            .tc_tracker_header .tc_header_text_logo {
              background: url(//assets.pivotaltracker.com/next/assets/next/30291959-textmark.svg) left center no-repeat;
              width: 116px;
              height: 35px;
              padding-right: 7px;
                        
              margin-left: 5px;
              margin-top: -1px;
            }
                        
            .tc_tracker_header .tc_header_project_name {
              padding: 0 6px;
              text-overflow: ellipsis;
              overflow: hidden;
            }
                        
            .tc_tracker_header .tc_header_logo{
              margin-right: 2px;
            }
                        
            .tc_feedback_modal {
              width: 540px;
              margin-left: -250px;
            }
                        
            .tc_feedback_modal h4 {
              font-size: 12px;
              font-weight: 700;
              margin-bottom: 2px;
              margin-top: 18px;
              margin-left: 18px;
            }
                        
            .tc_feedback_modal p {
              margin-left: 18px;
              font-size: 12px;
              padding-bottom: 5px;
              line-height: 17px;
            }
                        
            .tc_feedback_modal header h1:before {
              content: '';
              background-size: 14px 12px;
              width: 14px;
              height: 12px;
              float: left;
              margin-right: 7px;
              background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAAMCAYAAABSgIzaAAABOElEQVQoFY2SvUoDQRDHZ+aWhAQb7wHiByd4d2QJ6XwRO8HeA2t9AGuL2AtWipVY+QJiHTwiuVzExiZdPE7hbta9wIblIphtZmb3958ZdgaVUmBOkiQBCYpAQZeV2q/uCXEECEMueOB5XmxYrIRxHG80W60BIhxVrHmsWZ0Lbn7yPAqC4MsJw9DpbHXuEfFQg1iD7VAj0BNCSNd1b/FtPD4Twrmwif/8oijPhSA6tsHd7Z0/q6bv0+VnVBqcTNNMC9u2eA3/WzDwiID6Bl6nIrD6EAR4p0VLod2SSVa3BfO14266L92elAi4mFsdWomVetzzvMjMsdFoN5/tllcEoBvUc8zm8xMpZbYQVtAkTS+R8NQWMPPM2pwrvTmv5l1Ujl61PhEdKOaxDocllQ9lXj75vv9pwLr9BQzQenxz2KjPAAAAAElFTkSuQmCC') no-repeat;
            }
                        
            .tc_feedback_modal_content_wrapper {
              /*padding: 15px 15px 13px 15px;*/
            }
                        
            .tc_feedback_modal_form {
              position: relative;
              margin: 15px 15px 13px;
              padding: 0;
            }
                        
            .tc_feedback_modal_content {
              position: relative;
              overflow: auto;
            }
                        
            .tc_feedback_modal_left {
              float: left;
              width: 33%;
              margin-bottom: 16px;
            }
                        
            .tc_feedback_modal_right {
              float: right;
              width: 66%;
            }
                        
            .tc_feedback_modal_blend {
              color: inherit;
            }
                        
            .tc_feedback_modal_useful_link {
              color: #135d88;
              text-decoration: none;
            }
                        
            .tc_feedback_description {
              border-color: #ccc;
              width: 558px;
              resize: none;
              font-family: open-sans,EmojiFontFace,helvetica,arial,sans-serif;
              font-size: 12px;
              line-height: 18px;
              margin: 0;
              padding: 2px 4px 1px;
              width: 100%;
              background-color: #fff;
              border: 1px solid #ccc;
              box-sizing: border-box;
              border-radius: 3px;
              margin: 0 0 30px;
              height: 210px;
            }
                        
            .tc_feedback_thank_you_message {
              font-size: 12px;
              line-height: 17px;
              position: relative;
              margin: 15px 15px 13px;
              padding: 0;
              height: 230px;
            }
                        
            .tc_feedback_buttons {
              text-align: right;
              line-height: 24px;
              position: absolute;
              height: 24px;
              right: 0;
              bottom: 0;
            }
                        
            .tc_feedback_buttons .tc_feedback_submit,
            .tc_feedback_buttons .tc_feedback_close {
              float: right;
              border-radius: 0;
              box-shadow: none;
              border: 1px solid #476901;
              background-color: #678E00;
              color: #fff;
              padding: 0 15px;
              font-weight: 600;
              height: 23px;
              cursor: pointer;
            }
                        
            .tc_feedback_buttons .tc_feedback_submit:hover,
            .tc_feedback_buttons .tc_feedback_close:hover {
              background-color: #79a800;
            }
                        
            .tc_feedback_buttons .tc_feedback_cancel {
              color: #AAAAAA;
              font-weight: 600;
              background-color: transparent;
              border: 1px solid transparent;
              height: 16px;
              margin-top: 4px;
              margin-right: 10px;
              padding: 0;
              box-sizing: border-box;
              cursor: pointer;
              line-height: 15px;
              font-size: 10px;
            }
                        
            .tc_feedback_modal .role_label {
              padding-top: 5px;
            }
                        
            .tc_create_project_modal > section {
              padding: 15px 21px;
              border-bottom: 1px solid #ddd;
            }
            .tc_create_project_modal > footer {
              padding: 8px 9px;
              font-size: 11px;
              background-color: #fff;
              text-align: right;
              border-bottom-left-radius: 4px;
              border-bottom-right-radius: 4px;
            }
            .tc_create_project_modal input[type=checkbox] {
              display: inline-block;
            }
            .tc_create_project_modal input[type=checkbox] + label {
              display: inline-block;
            }
                        
            .tc_create_project_modal_message {
              background: #FFEEEE;
              border: 1px solid #DD7270;
              box-sizing: border-box;
              position: absolute;
              top: 10px;
              right: 10px;
              width: 223px;
              max-height: 91%;
              min-height: 53px;
              padding: 9px 11px;
              font-size: 11px;
              font-weight: normal;
              line-height: 16px;
            }
            .tc_create_project_modal_message p {
              font-size: 11px;
            }
                        
            .tc-account-selector__helper-text,
            .tc-account-creator__info {
              color: #666;
              font-size: 12px;
              line-height: initial;
              padding: 8px 0 0 16px;
            }
                        
            .tc-form-modal__error {
              color: #BD0000;
              font-size: 12px;
              line-height: initial;
              margin-bottom: 16px;
              padding-top: 8px;
            }
                        
            .tc-form-modal__error p {
              font-size: 12px;
            }
                        
            .tc-account-selector {
              position: relative;
              font-size: 14px;
              font-weight: normal;
              color: #333;
            }
                        
            .tc-account-selector__options {
              background-color: #FFF;
              box-sizing: border-box;
              position: absolute;
              top: 0;
              z-index: 1000;
              box-shadow: 0 0 4px 0 #6A6A6A;
              border-radius: 2px;
              max-height: 250px;
              overflow-y: scroll;
              width: 497px;
            }
                        
            .tc-account-selector__list-header {
              padding: 8px 16px 0 16px;
              display: flex;
              cursor: default;
              color: #6A6A6A;
              height: 40px;
            }
                        
            .tc-account-selector__list-header-name {
              flex-grow: 2;
            }
                        
            .tc-account-selector__list-header-owner {
              font-style: italic;
              width: 100px;
            }
                        
            .tc-account-selector__option-account {
              display: flex;
              padding: 0 16px 0 42px;
              cursor: pointer;
              line-height: 40px;
            }
                        
            .tc-account-selector__option-account--selected-yes {
              background-image: url(//assets.pivotaltracker.com/next/assets/next/ab0b23b0-selected-checkmark.svg);
              background-repeat: no-repeat;
              background-position: 16px 14px;
            }
                        
            .tc-account-selector__option-account:hover {
              background-color: #EFEFEF;
            }
                        
            .tc-account-selector__option-account-name {
              width: 324px;
              overflow: hidden;
              text-overflow: ellipsis;
              white-space: nowrap;
              margin-right: 15px;
            }
                        
            .tc-account-selector__option-account-owner {
              width: 100px;
              color: #6A6A6A;
              font-size: 13px;
              font-style: italic;
              overflow: hidden;
              text-overflow: ellipsis;
              white-space: nowrap;
            }
                        
            .tc-account-selector__header {
              background-color: #fff;
              background-image: url(//assets.pivotaltracker.com/next/assets/next/376ba07c-selectbox-arrow.svg);
              background-repeat: no-repeat;
              background-position: 97% 55%;
              border: 1px solid #ccc;
              border-radius: 2px;
              box-sizing: border-box;
              color: #6A6A6A;
              font-size: 14px;
              font-weight: normal;
              line-height: 34px;
              padding: 0 16px;
            }
                        
            .tc-account-selector__header--selected {
              color: #333;
              overflow: hidden;
              text-overflow: ellipsis;
              width: 497px;
              padding-right: 35px;
              white-space: nowrap;
            }
                        
            .tc-account-selector__header:hover {
              background-image: url(//assets.pivotaltracker.com/next/assets/next/a2bf6a02-selectbox-arrow-hover.svg);
            }
                        
            .tc-account-selector__header:hover {
              border-color: #4A90E2;
            }
                        
            .tc-account-selector--error .tc-account-selector__header {
              background-color: #FFEEEE;
              border-color: #DF716E;
            }
                        
            .tc-account-selector--error .tc-account-selector__header:hover {
              background-image: url(//assets.pivotaltracker.com/next/assets/next/376ba07c-selectbox-arrow-hover-error.svg);
            }
                        
            .tc-account-selector__create-account {
              padding: 16px 16px 0 16px;
              line-height: 21px;
              height: 53px;
              border-top: 1px solid #DDD;
              color: #6A6A6A;
              box-sizing: border-box;
              cursor: pointer;
              display: flex;
            }
                        
            .tc-account-selector__create-account-text {
              flex-grow: 2;
            }
                        
            .tc-account-selector__create-account-icon {
              background-image: url(//assets.pivotaltracker.com/next/assets/next/ca025359-create-account.svg);
              background-repeat: no-repeat;
              background-position: center center;
              background-color: #E2E9EE;
              display: inline-block;
              width: 26px;
              height: 26px;
              border-radius: 2px;
            }
                        
            .tc-account-selector__create-account:hover {
              background-color: #E8F7F1;
              color: #2AB27B;
            }
                        
            .tc-account-selector__create-account:hover .tc-account-selector__create-account-icon {
              background-image: url(//assets.pivotaltracker.com/next/assets/next/7d3680a1-create-account-white.svg);
              background-color: #2AB27B;
            }
                        
            .tc-account-creator {
              font-size: 14px;
              color: #333;
            }
                        
            input[type=text].tc-account-creator__name { /* Arms race in non_stories_view.css */
              font-size: 14px;
              width: 100%;
              background-color: #E8F7F1;
              border: 1px solid #1EB379;
              border-radius: 2px;
              padding: 8px 16px;
              box-sizing: border-box;
            }
                        
            input[type=text].tc-account-creator__name:hover {
              border-color: #1EB379;
            }
                        
            input[type=text].tc-account-creator__name:focus {
              border-color: #1EB379;
              box-shadow: 0 0 4px 0 #1EB379;
            }
                        
            .tc-account-creator--state-error input[type=text].tc-account-creator__name {
              background-color: #FFEEEE;
              border-color: #DF716E;
            }
                        
            .tc-account-creator--state-error input[type=text].tc-account-creator__name:focus {
              box-shadow: 0 0 4px 0 #DF716E;
            }
                        
            .tc-account-creator__back-to-selector {
              color: #346DA9;
              cursor: pointer;
              padding-left: 2px;
            }
                        
            .tc-workspace-modal__message {
              font-size: 1.2em;
              line-height: 1.4;
              margin-bottom: 1em;
            }
                        
            .tc-form-modal {
              width: 545px;
            }
                        
            .tc-form-modal .tc_modal_content header{
              color: #333;
              font-weight: 100;
              padding: 12px 23px;
              background-color: #FFF;
              border-top-left-radius: 4px;
              border-top-right-radius: 4px;
              box-shadow: 0 2px 5px rgba(0, 0, 0, 0.4);
              border-bottom: 1px solid #DDD;
            }
                        
            .tc-form-modal .tc_modal_content header h1{
              line-height: 36px;
              font-size: 22px;
              font-weight: normal;
              letter-spacing: -0.02em;
              text-rendering: optimizeLegibility;
            }
                        
            .tc-form-modal__content {
              user-select: text;
              text-rendering: optimizeLegibility;
              z-index: 100;
              overflow: auto;
              color: #000;
              background-color: #F6F6F6;
              position: relative;
            }
                        
            .tc-form-modal__section {
              padding: 24px 24px 0 24px;
            }
                        
            .tc-account-chooser__group-name,
            .tc-project-type-chooser__group-name,
            .tc-project-name__label-name {
              color: #666;
              line-height: 24px;
              font-size: 12px;
              font-weight: 600;
              display: block;
            }
                        
            .tc-project-type-chooser__joinAs {
              margin-top: 5px;
                        
              border-radius: 3px;
              border: 1px solid #ccc;
              font-family: inherit;
              font-size: inherit;
              line-height: inherit;
              padding: 4px 5px;
            }
                        
            .tc-project-name,
            .tc-account-chooser {
              padding: 0 0 30px 0;
            }
                        
            .tc-form-modal input:hover {
              border-color: #4A90E2;
            }
                        
            .tc-form-modal input:focus {
              outline: 0;
              border-color: #4A90E2;
              box-shadow: 0 0 4px 0 #4A90E2;
            }
                        
            .tc-form-modal footer.tc-form-modal-footer {
              padding: 7px 9px;
              background-color: #fff;
              text-align: right;
              border-bottom-left-radius: 4px;
              border-bottom-right-radius: 4px;
              border-top: 1px solid #DDD;
            }
                        
            .tc-project-type-chooser {
              padding: 0 0 14px 0;
            }
                        
            .tc-project-type-chooser__account-name {
              font-weight: 600;
            }
                        
            .tc-project-type-chooser__label {
              display: block;
              padding: 16px 0 6px 8px;
              font-size: 13px;
              line-height: 20px;
            }
                        
            .tc-project-type-chooser__label input[type='radio'] {
              font-size: inherit;
              line-height: inherit;
            }
                        
            .tc-project-type-chooser__label-name {
              display: inline-block;
              font-weight: 600;
              padding-left: 42px;
              vertical-align: text-bottom;
              position: relative;
              color: #333;
              overflow: visible;
              font-size: 13px;
            }
                        
            .tc-project-type-chooser__icon {
              display: block;
              position: absolute;
              top: 7px;
              left: 14px;
              background-repeat: no-repeat;
              width: 14px;
              height: 14px;
            }
                        
            .tc-project-type-chooser__icon--public {
              background-image: url(//assets.pivotaltracker.com/next/assets/next/61fd5629-earth.svg);
            }
                        
            .tc-project-type-chooser__icon--shared {
              background-image: url(//assets.pivotaltracker.com/next/assets/next/8792bac6-project-type-shared.svg);
            }
                        
            .tc-project-type-chooser__icon--private {
              background-image: url(//assets.pivotaltracker.com/next/assets/next/0a401287-project-type-private.svg);
            }
                        
                        
            .tc-project-type-chooser__label-description {
              display: block;
              padding-left: 60px;
              color: #666;
              font-weight: 100;
            }
                        
                        
            .tc-form-modal-footer__button--cancel {
              background: none;
              border-radius: 2px;
              color: #888;
              margin-right: 16px;
            }
                        
            .tc-form-modal-footer__button--submit {
              background-color: #3676c0;
              border-radius: 2px;
              color: #fff;
            }
                        
            .tc-form-modal-footer__button {
              cursor: pointer;
              border: none;
              transition: opacity 0.2s;
              font-size: 14px;
              height: 33px;
              padding: 0 10px 0 11px;
            }
            .tc-form-modal-footer__button:hover {
              opacity: 0.90;
            }
                        
            input.tc-form__input--error:hover {
              border-color: #DD7270;
            }
                        
            input.tc-form__input--error:focus {
              border-color: #DD7270;
              box-shadow: 0 0 4px 0 #DD7270;
            }
                        
            .tc-form__input--error-message {
              color: #BD0000;
              font-size: 12px;
              line-height: initial;
              margin-bottom: 16px;
              padding-top: 8px;
            }
                        
            .noscroll {
              overflow: hidden;
            }
            </style>

            <style type='text/css'>
            .LY99G__DropdownMenu__divider {
              border-top: 1px solid #e3e3e3;
              margin: 3px -4px;
            }
                        
            .w39lj__DropdownMenuOption {
              margin: 0;
              font-size: 11px;
              line-height: 29px;
              text-decoration: none;
              padding: 0 10px 0 5px;
              color: #000;
              border: 1px solid transparent;
              border-radius: 2px;
              text-align: left;
              display: flex;
              align-items: center;
            }
                        
            .w39lj__DropdownMenuOption:hover {
              background: #eef0f1;
            }
                        
            .w39lj__DropdownMenuOption:focus {
              outline: none;
              border: 1px solid #CDE5FF;
              box-shadow: 0 0 1px 2px #83BEFF;
            }
                        
            ._1QVnB__DropdownMenuOption--disabled {
              cursor: default;
              margin: 0;
              font-size: 11px;
              line-height: 29px;
              text-decoration: none;
              padding: 0 10px 0 5px;
              color: #AAA;
              text-align: left;
                        
              display: flex;
              align-items: center;
            }
                        
            ._1QVnB__DropdownMenuOption--disabled:hover {
              background: inherit;
            }
                        
            .rn6KW__DropdownMenuOption__text {
              margin-left: 5px;
            }
                        
            ._3Sa3d__DropdownMenu__menuList {
              position: absolute;
              right: 0;
              z-index: 100;
              background: #FFF;
              box-shadow: 0 2px 5px rgba(0, 0, 0, 0.4);
              border-radius: 4px;
              width: 207px;
              padding: 4px;
              z-index: 100;
              overflow: visible;
              border-top: 1px solid #ccc;
            }
                        
            ._3Oarf__DropdownMenu__menuList--wide {
              width: 350px;
              padding: initial;
            }
                        
            ._1keuU__DropdownMenu__menuList--left {
              left: 0;
              right: initial;
            }
                        
            ._35-z3__DropdownMenu__menuList--top {
              bottom: 0;
            }
                        
            ._3Sa3d__DropdownMenu__menuList:before {
              z-index: 100;
              position: absolute;
              top: -5px;
              right: 24px;
              width: 0;
              height: 0;
              content: '';
            }
                        
            ._3Sa3d__DropdownMenu__menuList:after {
              z-index: 100;
              position: absolute;
              top: -4px;
              right: 25px;
              width: 0;
              height: 0;
              content: '';
            }
                        
            ._1keuU__DropdownMenu__menuList--left:before {
              left: 24px;
              right: initial;
            }
                        
            ._1keuU__DropdownMenu__menuList--left:after {
              left: 25px;
              right: initial;
            }
                        
            ._3Sa3d__DropdownMenu__menuList hr {
              border: 0;
              border-bottom: 1px solid #EEE;
              margin: 0;
            }
                        
            ._3Sa3d__DropdownMenu__menuList button {
              background: none;
              border: none;
              width: 100%;
              text-align: left;
              cursor: pointer;
            }
                        
            ._3Sa3d__DropdownMenu__menuList a,
            ._3Sa3d__DropdownMenu__menuList button {
              margin: 0;
              font-size: 12px;
              line-height: 22px;
              text-decoration: none;
              padding: 5px 10px;
              color: #000;
              display: block;
            }
                        
            ._3Sa3d__DropdownMenu__menuList a:hover,
            ._3Sa3d__DropdownMenu__menuList button:hover {
              background: #eef0f1;
            }
            </style>

            <style type='text/css'>
            ._3P6yz__DropdownWithContent {
              z-index: 101;
              position: absolute;
                        
              padding: 16px;
              border: 1px solid #ddd;
                        
              box-shadow: 0 4px 6px rgba(0, 0, 0, 0.14);
              background-color: #fff;
            }
                        
            ._1LwX0__DropdownWithContent__title {
              font-size: 14px;
              margin: 0 0 8px 0;
              font-weight: 600;
            }
            </style>

            <style type='text/css'>
            .gPvfj__FadeinImage__container {
              position: relative;
              height: 100%;
              width: 100%;
              display: flex;
            }
                        
            .lYIfi__FadeinImage__image {
              max-height: 100%;
              max-width: 100%;
              height: auto;
              width: auto;
              margin: auto;
              align-self: center;
            }
                        
            ._3GH_F__FadeinImage__backgroundImage {
              height: 100%;
              width: 100%;
              background-size: contain;
              background-repeat: no-repeat;
              background-position: center;
            }
                        
            ._3rlAN__FadeinImage__placeholder {
              transition: opacity 0.5s linear;
              position: absolute;
              height:100%;
              width: 100%;
              top: 0;
              left: 0;
              background-repeat: no-repeat;
              background-position: center;
            }
                        
            ._3NuMe__FadeinImage__placeholder--faded {
              opacity: 0;
            }
            </style>

            <style type='text/css'>
            .dPZZt__TC-FixedFooterModal {
              width: 100%;
              max-height: inherit;
              margin: 0;
              border-radius: 2px;
              box-shadow: 0 4px 20px rgba(0, 0, 0, 0.8);
              overflow: hidden;
              font-size: 13px;
              display: flex;
              flex-direction: column;
            }
                        
            ._2LvD3__TC-FixedFooterModal__header {
              background-color: #FFF;
              color: #333;
              padding: 16px 16px;
              display: flex;
              flex-direction: column;
              align-items: flex-start;
              border-bottom: 1px solid #ddd;
              flex: 1 0 auto;
            }
                        
            ._1WQjZ__TC-FixedFooterModal__header--center {
              align-items: center;
            }
                        
            .GwBgq__TC-FixedFooterModal__header--withSubtitle {
              padding: 24px 24px;
            }
                        
            ._3e-qs__TC-FixedFooterModal__header--title {
              font-size: 20px;
              font-weight: 400;
              letter-spacing: -0.02em;
              -webkit-font-smoothing: antialiased;
              margin: 0;
            }
                        
            ._2axf9__TC-FixedFooterModal__header--subtitle {
              font-size: 14px;
              font-weight: 400;
              letter-spacing: -0.02em;
              margin-top: 8px;
              -webkit-font-smoothing: antialiased;
              word-break: break-all;
              color: rgba(51, 51, 51, 0.7);
            }
                        
            .sqceD__TC-FixedFooterModal__body {
              background-color: #fafafa;
              flex: 1 1 auto;
              display: flex;
              overflow: auto;
            }
                        
            @media all and (-ms-high-contrast: none), (-ms-high-contrast: active) {
              .sqceD__TC-FixedFooterModal__body {
                max-height: 60vh;
              }
            }
                        
            @media (-ms-high-contrast: none) and (max-height: 730px) {
              .sqceD__TC-FixedFooterModal__body > * {
                max-height: 300px;
              }
            }
                        
            @media (-ms-high-contrast: none) and (max-height: 550px) {
              .sqceD__TC-FixedFooterModal__body > * {
                max-height: 150px;
              }
            }
                        
            .F3Pqi__TC-FixedFooterModal__body--noscroll {
              overflow: inherit;
            }
                        
            ._2xf8___TC-FixedFooterModal__footer {
              border-top: 1px solid #ddd;
              display: flex;
              justify-content: space-between;
              padding: 8px;
              font-size: 11px;
              background-color: #fff;
              text-align: right;
              position: relative;
              flex: 1 0 auto;
            }
            </style>

            <style type='text/css'>
            .SEEZC__FeedbackDowngradeModal {
              font-size: 14px;
              padding: 24px;
              width: 100%;
            }
                        
            ._1qLxX__FeedbackDowngradeModal__text {
              font-size: 12px;
            }
                        
            ._3hEk___FeedbackDowngradeModal__label {
              font-weight: 600;
              color: #686868;
              margin-bottom: 5px;
              display: block;
              font-size: 12px;
              line-height: 17px;
            }
                        
                        
            ._3EgSr__FeedbackDowngradeModal__input,
            .R9uJ1__FeedbackDowngradeModal__textarea {
              width: 80%;
              margin-bottom: 20px;
              font-size: 14px;
              padding: 5px;
              border: 1px solid #CCC;
            }
                        
            .R9uJ1__FeedbackDowngradeModal__textarea {
              height: 50px;
              resize: none;
            }
                        
            ._3EgSr__FeedbackDowngradeModal__input {
                        
            }
                        
            ._3-NWx__FeedbackDowngradeModal__select {
              font-size: 14px;
              padding: 5px;
            }
            </style>

            <style type='text/css'>
            ._1NKYS__Flash__container {
              width: 100%;
              left: 0;
              right: 0;
              margin-left: auto;
              margin-right: auto;
              display: flex;
              justify-content: center;
              pointer-events: none;
              font-size: 14px;
                        
              animation: _2s0t6__fadein 1s;
            }
                        
            ._1KVFQ__Flash__container--fadeout {
              animation: _2FoQt__fadeout 1s;
            }
                        
            ._9omJY__Flash {
              position: absolute;
              top: 10px;
              background-color: #F9EDBE;
              min-width: 175px;
              min-height: 24px;
              display: flex;
              justify-content: center;
              align-items: center;
              border-radius: 3px;
              box-shadow: 1px 2px 13px 0 #666;
              padding: 5px 5px 5px 8px;
              color: #333;
            }
                        
            ._3zOgC__Flash__MessageContainer {
              padding-right: 8px;
            }
                        
            @keyframes _2s0t6__fadein {
                from { opacity: 0; }
                to   { opacity: 1; }
            }
                        
            @keyframes _2FoQt__fadeout {
                from { opacity: 1; }
                to   { opacity: 0; }
            }
            </style>

           <style type='text/css'>
            ._3iG1d__IconButton__icon {
              vertical-align: middle;
            }
            </style>

            <style type='text/css'>
            ._257Dx__projectNavTab {
              display: inline-block;
              height: 20px;
              padding: 4px 0 0px 23px;
                        
              font-size: 11px;
              font-weight: 600;
              text-decoration: none;
              text-transform: uppercase;
                        
              color: #CADCE4;
              transition: color 0.1s ease-in;
              -moz-transition: color 0.1s ease-in;
              -webkit-transition: color 0.1s ease-in;
              -o-transition: color 0.1s ease-in;
            }
                        
            ._257Dx__projectNavTab:hover {
              text-decoration: none;
              color: #FFF;
            }
                        
            ._257Dx__projectNavTab:after {
              content: '';
              height: 12px;
              width: 1px;
                        
              display: inline-block;
              margin-left: 22px;
              position: relative;
              top: 2px;
                        
              background-color: #fff;
              opacity: 0.10;
            }
                        
            ._2l-eS__projectNavTab--current {
              margin-top: 3px;
              color: #FFF;
              border-bottom: 3px solid #9FB8C8;
              cursor: default;
            }
                        
            ._2l-eS__projectNavTab--current._1yG48__projectNavTab--clickable {
              cursor: pointer;
            }
            </style>";
                    
                    $user_agent .= "<style type='text/css'>
            ._199kd__projectNavExpanded {
              display: block;
              clear: both;
                        
              font-size: 11px;
              line-height: 11px;
                        
              position: absolute;
              top: 30px;
            }
                        
            ._GPfz__projectNav__toggle {
              background-image: url(//assets.pivotaltracker.com/next/assets/next/fb94c441-proj-nav-toggle-arrow.svg);
              background-repeat: no-repeat;
              background-position: center;
                        
              width: 40px;
              height: 10px;
                        
              display: inline-block;
                        
              cursor: pointer;
                        
              opacity: 0.6;
              transition: opacity .05s ease-in;
              -moz-transition: opacity .05s ease-in;
              -webkit-transition: opacity .05s ease-in;
              -o-transition: opacity .05s ease-in;
            }
                        
            ._3-iOd__projectNav__toggleWrapper {
              display: inline-block;
              height: 20px;
              cursor: pointer;
            }
                        
            ._GPfz__projectNav__toggle:hover {
              opacity: 1.0;
            }
            </style>

            <style type='text/css'>
            .n_Tg8__projectNavCollapsed__trayButton {
              padding: 12px 1px 7px 0;
              margin: 0;
              position: relative;
              cursor: pointer;
              font-size: 16px;
                        
              transition: background-color 0.1s ease-in-out;
              -moz-transition: background-color 0.1s ease-in-out;
              -webkit-transition: background-color 0.1s ease-in-out;
              -o-transition: background-color 0.1s ease-in-out;
            }
                        
            ._3Lg8Q__projectNavCollapsed__expandButtonLabel {
              vertical-align: middle;
              cursor: pointer;
            }
                        
            .dB5Da__projectNavCollapsed__expandButton {
              cursor: pointer;
              display: inline-block;
              padding-right: 15px;
            }
                        
            ._13XKY__projectNavCollapsed__arrow {
              display: inline-block;
              background-image: url(//assets.pivotaltracker.com/next/assets/next/11b99648-proj-nav-collapsed-arrow.svg);
              width: 9px;
              height: 10px;
              background-size: 9px 10px;
              background-repeat: no-repeat;
              margin-left: 12px;
              position: relative;
              top: 1px;
                        
              opacity: 0.6;
              transition: opacity .05s ease-in;
              -moz-transition: opacity .05s ease-in;
              -webkit-transition: opacity .05s ease-in;
              -o-transition: opacity .05s ease-in;
            }
                        
            .dB5Da__projectNavCollapsed__expandButton:hover ._13XKY__projectNavCollapsed__arrow {
              opacity: 1.0;
            }
                        
            ._2Q1uC__projectNavCollapsed__arrow--expanded {
              margin-left: 13px;
            }
                        
            ._1y_mn__projectNavCollapsed__trayButtonLabel {
              position: relative;
              font-size: 11px;
              line-height: 11px;
              font-weight: 600;
              text-decoration: none;
              text-transform: uppercase;
              user-select: none;
                        
              padding-right: 18px;
                        
              color: #CADCE4;
              transition: color .05s ease-in;
              -moz-transition: color .05s ease-in;
              -webkit-transition: color .05s ease-in;
              -o-transition: color .05s ease-in;
            }
                        
            .n_Tg8__projectNavCollapsed__trayButton:hover ._1y_mn__projectNavCollapsed__trayButtonLabel {
              color: #fff;
            }
                        
            ._1y_mn__projectNavCollapsed__trayButtonLabel:after {
              content: '';
              width: 0;
              height: 0;
                        
              position: absolute;
              top: 41%;
              right: 5px;
                        
              border-style: solid;
              border-width: 4px 3px 0 3px;
              border-color: rgba(255, 255, 255, 0.4) transparent transparent transparent;
            }
                        
            ._35HZ-__projectNavCollapsed__trayButton--seperated:before {
              content: '';
              height: 12px;
              width: 1px;
                        
              display: inline-block;
              vertical-align: middle;
              margin-right: 14px;
                        
              background-color: #fff;
              opacity: .2;
            }
                        
            ._35HZ-__projectNavCollapsed__trayButton--seperated:after {
              content: '';
              height: 12px;
              width: 1px;
                        
              display: inline-block;
              vertical-align: middle;
              margin-left: 7px;
                        
              background-color: #fff;
              opacity: .2;
            }
                        
            /* Expanded Tray */
                        
            ._3HYNM__projectNavCollapsed__trayButton--expanded {
              background-color: #396B8A;
              padding-right: 8px;
              padding-left: 15px;
              z-index: 101;
                        
              transition: background-color 0.1s ease-in-out;
              -moz-transition: background-color 0.1s ease-in-out;
              -webkit-transition: background-color 0.1s ease-in-out;
              -o-transition: background-color 0.1s ease-in-out;
            }
                        
            .mBUQ0__projectNavCollapsed__tray {
              width: 100%;
              position: absolute;
              z-index: 101;
              top: 34px;
                        
              background-color: #396B8A;
              display: block;
              clear: both;
                        
              font-size: 11px;
              line-height: 11px;
                        
              box-shadow: 0px 5px 5px 1px rgba(0,0,0,0.55);
                        
                        
              opacity: 1.0;
                        
              -moz-transition: opacity .08s ease-in-out;
              -webkit-transition: opacity .08s ease-in-out;
              -o-transition: opacity .08s ease-in-out;
              transition: opacity .08s ease-in-out;
            }
                        
            .CQ8ov__projectNavCollapsed__tray--hidden {
              z-index: -1;
              opacity: 0.0;
            }
            </style>

            <style type='text/css'>
            ._30HLr__ProductUpdatesDropdown__header {
              color: black;
              padding: 15px;
              font-weight: 600;
              font-size: 18px;
              line-height: 32px;
              display: flex;
              justify-content: space-between;
            }
                        
            ._3ocCq__ProductUpdatesDropdown__productUpdate {
              color: #828282;
              border-top: 1px solid #CCC;
              padding: 15px;
              line-height: 16px;
              position: relative;
              width: 350px;
              white-space: normal;
              word-wrap: break-word;
            }
                        
            ._2gioK__ProductUpdatesDropdown__productUpdate--recent {
              background-color: #F4F4F4;
            }
                        
            ._2gioK__ProductUpdatesDropdown__productUpdate--recent:after {
              background-color: #F98D3F;
              position: absolute;
              right: 10px;
              top: 10px;
                        
              margin-left: 10px;
              content: '';
                        
              width: 10px;
              height: 10px;
              border-radius: 7px;
            }
                        
            ._2_LSZ__ProductUpdatesDropdown__publishedAt {
              font-size: 11px;
            }
                        
            a._2B9Hx__ProductUpdatesDropdown__headerLink {
              font-size: 13px;
              text-decoration: none;
              color: #618CB3;
              font-weight: normal;
                        
              padding:  0 10px;
            }
                        
            a._2uGJ2__ProductUpdatesDropdown__blogLink {
              font-size: 14px;
              padding: 0;
              text-decoration: none;
              color: #618CB3;
              font-weight: 600;
              line-height: 22px;
            }
                        
            a._2B9Hx__ProductUpdatesDropdown__headerLink:hover,
            a._2uGJ2__ProductUpdatesDropdown__blogLink:hover {
              background: inherit;
              text-decoration: underline;
            }
                        
            ._2GcEE__ProductUpdatesDropdown__description {
              padding-top: 5px;
              font-size: 13px;
            }
                        
            ._2uo_D__ProductUpdatesDropdown__indicator,
            ._1WzQ9__ProductUpdatesDropdown__indicator--newHeader {
              position: relative;
            }
                        
            ._2uo_D__ProductUpdatesDropdown__indicator:after,
            ._1WzQ9__ProductUpdatesDropdown__indicator--newHeader:after {
              background-color: #F98D3F;
              position: absolute;
              right: 5px;
              top: 5px;
              border: 2px solid #256188;
                        
                        
              margin-left: 10px;
              content: '';
                        
              width: 10px;
              height: 10px;
              border-radius: 7px;
            }
                        
            ._1WzQ9__ProductUpdatesDropdown__indicator--newHeader:after {
              border: 2px solid #3E7293;
            }
            </style>

            <style type='text/css'>
            ._2bjPv__tc_projects_dropdown {
                    padding-right: 17px;
            }
                        
            ._3bJRd__tc_guest_context_link {
              color: #fff;
                    height: 100%;
                    position: relative;
                    display: inline-block;
                    max-width: 221px;
                    overflow: hidden;
                    float: left;
                    text-overflow: ellipsis;
            }
                        
            .euxgW__tc_public_project_label {
                    margin-left: 4px;
            }
            </style>

            <style type='text/css'>
            ._2-n1Z__TextField__label {
              display: block;
              color: #686868;
              font-size: 13px;
              font-weight: 600;
              line-height: 19.5px;
              vertical-align: baseline;
              margin-bottom: 4px;
            }
                        
            ._14TvU__TextField__input {
              padding: 10px 15px;
              font-size: 14px;
              color: #666;
              width: 100%;
              box-sizing: border-box;
              border-radius: 3px;
              border: 1px solid #ccc;
            }
                        
            ._14TvU__TextField__input:disabled {
              background: #eee;
              cursor: not-allowed;
            }
                        
                        
            .oQjsG__TextField__input--erroneous {
              background: #FFEEEE;
              border-color: #DF716E;
            }
                        
            ._1UhXE__TextField__form-text {
              font-size: 13px;
              padding-bottom: 4px;
              padding-top: 4px;
              margin: 0;
              color: #333;
            }
                        
            .pU4hm__TextField__form-text--error {
              color: #BD0000;
            }
            </style>

            <style type='text/css'>
            .Ib09Z__NotificationsTitleBar {
              background-color: #908674;
              font-weight: 600;
              line-height: 32px;
              overflow: hidden;
              text-transform: uppercase;
              font-size: 12px;
              color: white;
              padding: 0 10px;
            }
                        
            .Ib09Z__NotificationsTitleBar:before {
              background-image: url(//assets.pivotaltracker.com/next/assets/next/db3d396d-brown_arrow_up.png);
              content: '';
              position: absolute;
              background-size: 9px 6px;
              background-repeat: no-repeat;
              width: 9px;
              height: 6px;
              top: 0;
              right: 0;
              margin-right: 28px;
              margin-top: -6px;
            }
                        
            ._VpJF__NotificationsTitleBar__settingsLink {
              float: right;
              margin: 10px;
            }
                        
            ._VpJF__NotificationsTitleBar__settingsLink:before {
              content: '';
              position: absolute;
              background-size: 12px 12px;
              background-repeat: no-repeat;
              background-image: url(//assets.pivotaltracker.com/next/assets/next/ec1fa145-gear_white.png);
              width: 12px;
              height: 12px;
              opacity: .8;
            }
                        
            ._VpJF__NotificationsTitleBar__settingsLink:hover:before {
              opacity: 1;
            }
            </style>

            <style type='text/css'>
            ._1p-x2__Notification {
              background-color: #ecf5fb;
              cursor: pointer;
              color: #333;
              border-bottom: 1px solid #ddd;
              font-size: 12px;
              padding: 10px 10px 10px 35px;
              position: relative;
              white-space: normal;
              line-height: 14px;
            }
                        
            ._2GfT6__Notification--read {
              background-color: #f7f7f7;
              cursor: default;
            }
                        
            ._1QTQc__Notification__type {
              position: absolute;
              left: 10px;
              top: 10px;
              background-size: 17px 15px;
              background-repeat: no-repeat;
              width: 17px;
              height: 15px;
            }
                        
            ._1zvkA__Notification__createdAt {
              float: right;
              color: #aaa;
              font-size: 10px;
              line-height: 14px;
            }
                        
            ._2A7S4__Notification__projectName {
              color: #888;
              margin-bottom: 2px;
              margin-right: 5px;
              height: 14px;
              line-height: 14px;
              font-weight: 600;
              overflow: hidden;
              text-overflow: ellipsis;
              white-space: nowrap;
            }
                        
            ._1374W__Notification__markAsRead {
              display: block;
              width: 20px;
              height: 20px;
              margin-left: -2px;
              position: absolute;
              left: 10px;
              top: 25px;
            }
            ._1374W__Notification__markAsRead:before {
              background-color: #298bb1;
              border-radius: 10px;
              content: '';
              height: 8px;
              position: absolute;
              left: 50%;
              margin-top: -4px;
              margin-left: -4px;
              top: 50%;
              width: 8px;
            }
                        
            ._1IOZR__Notification__details {
              line-height: 18px;
            }
                        
            ._1rxCH__Notification__detailsLink {
              color: #205f8a;
              text-decoration: none;
              word-wrap: break-word;
              font-weight: 600;
            }
                        
            ._14mAO__Notification__context {
            }
                        
            ._3oCPO__Notification__blockerContext {
              border-left: 3px solid #C6C2A9;
              padding-left: 3px;
              font-style: italic;
            }
                        
            ._2n6G1__Notification__attachmentIcon {
              position: relative;
              float: left;
              top: 3px;
              background-size: 12px 12px;
              background-repeat: no-repeat;
              background-image: url(//assets.pivotaltracker.com/next/assets/next/ddbf9c77-notifications-attachments.png);
              width: 12px;
              height: 12px;
            }
                        
            ._2ntvJ__Notification__attachmentCount {
              margin-left: 5px;
              color: #888;
            }
                        
            ._2gX2L__Notification__message--highlight {
              font-weight: 600;
              color: #698c00;
            }
                        
            /* Below is all the images being mapped to notification types */
                        
            /* STORIES */
            .nlZJj__Notification__type--story--acceptance,
            ._1xzmY__Notification__type--story--accepted {
              background-image: url(//assets.pivotaltracker.com/next/assets/next/f6645934-story_acceptance.png);
            }
                        
            .ZqHdu__Notification__type--story--rejection,
            ._2Wctm__Notification__type--story--unstarted,
            ._1xc5K__Notification__type--story--started,
            ._1z0WR__Notification__type--story--finished,
            ._3urLd__Notification__type--story--label,
            ._3Roej__Notification__type--story--task,
            ._3sMUq__Notification__type--story--create,
            .ETwYC__Notification__type--story--rejected,
            ._2Ao4p__Notification__type--story--delivered {
              background-image: url(//assets.pivotaltracker.com/next/assets/next/9be80428-story_actions.png);
            }
                        
            .lmaJN__Notification__type--story--delivery {
              background-image: url(//assets.pivotaltracker.com/next/assets/next/f6645934-story_delivery.png);
            }
                        
            .FtX6w__Notification__type--story--comment {
              background-image: url(//assets.pivotaltracker.com/next/assets/next/446d09db-story_comments.png)
            }
                        
            ._2QDkF__Notification__type--story--ownership {
              background-image: url(//assets.pivotaltracker.com/next/assets/next/ee0f8e8e-owner_requester.png)
            }
                        
            ._1eFZL__Notification__type--story--comment_with_mention {
              background-image: url(//assets.pivotaltracker.com/next/assets/next/fa6bc9bc-mention.png)
            }
                        
            /* BLOCKERS */
            ._2gSKT__Notification__type--story--blocker--update,
            ._28KM7__Notification__type--story--blocker--delete,
            ._1cCLe__Notification__type--story--blocker_with_mention,
            ._1RQ8J__Notification__type--story--blocker,
            .nLKt9__Notification__type--story--blocking {
              background-size: 18px 10px;
              background-repeat: no-repeat;
              width: 18px;
              height: 10px;
              margin-top: 2px;
            }
            ._1cCLe__Notification__type--story--blocker_with_mention,
            ._1RQ8J__Notification__type--story--blocker {
              background-image: url(//assets.pivotaltracker.com/next/assets/next/1931d421-blocked-icon.svg);
            }
            ._2gSKT__Notification__type--story--blocker--update,
            ._28KM7__Notification__type--story--blocker--delete {
              background-image: url(//assets.pivotaltracker.com/next/assets/next/14b1f1c7-blocked-icon-resolved.svg);
            }
            .nLKt9__Notification__type--story--blocking {
              background-image: url(//assets.pivotaltracker.com/next/assets/next/87e9b0e3-blocking-icon.svg);
            }
                        
            /* EPICS */
            ._3ShnY__Notification__type--epic--comment {
              background-image: url(//assets.pivotaltracker.com/next/assets/next/03ac76f2-epic_comments.png)
            }
            .tY0G0__Notification__type--epic--comment_with_mention {
              background-image: url(//assets.pivotaltracker.com/next/assets/next/fa6bc9bc-mention.png)
            }
                        
            /* MEMBERSHIP */
            ._1RfhG__Notification__type--membership {
              background-image: url(//assets.pivotaltracker.com/next/assets/next/bdfd660e-user.png)
            }
            </style>

            <style type='text/css'>
            ._1Aa7T__NotificationsList__markAllAsRead {
              margin: 0;
              text-transform: uppercase;
              text-decoration: none;
              line-height: 40px;
              text-align: center;
              color: #908674;
              font-size: 11px;
              font-weight: 700;
              box-sizing: border-box;
              padding: 0;
              border-radius: 0px;
              border: none;
              border-top: 1px solid #ddd;
            }
                        
            ._1Aa7T__NotificationsList__markAllAsRead:focus {
              outline: none;
            }
                        
            ._1Aa7T__NotificationsList__markAllAsRead:hover {
              border: none;
              background: #eee;
              border-top: 1px solid #ddd;
            }
                        
            ._3Ihew__NotificationsList__emptyMessage {
              padding: 0;
              min-height: 168px;
              max-height: 386px;
              overflow-x: hidden;
              overflow-y: auto;
              position: relative;
              background-color: #f3f3f3;
              border-bottom: 1px solid #ddd;
            }
                        
            ._3Ihew__NotificationsList__emptyMessage:after{
              content: 'You have no notifications.';
              color: #aaa;
              position: absolute;
              text-align: center;
              left: 0;
              right: 0;
              top: 50%;
              margin-top: -7px;
              font-weight: bold;
              font-size: 12px;
            }
            </style>

            <style type='text/css'>
            ._3weZY__NotificationsBell {
              position: relative;
              line-height: 26px;
            }
                        
            ._2Oy9G__NotificationsBell__button {
              background-image: url(//assets.pivotaltracker.com/next/assets/next/37e2c22b-notifications-bell.svg);
              background-repeat: no-repeat;
              background-position: center;
              height: 17px;
              min-width: 13px;
              position: relative;
              margin-top: 8px;
              opacity: .25;
              background-color: transparent;
            }
                        
            ._2Oy9G__NotificationsBell__button:focus {
              outline: none;
              opacity: .25;
            }
                        
            ._2Oy9G__NotificationsBell__button:hover {
              opacity: .35;
            }
                        
            ._2sG2R__NotificationsBell__dropdown {
              width: 320px;
              border: none;
              margin-top: 10px;
              right: -20px;
              padding: 0;
              border-radius: 0px;
            }
                        
            ._2km1J__NotificationsBell__notifications {
              min-height: 168px;
              max-height: 386px;
              overflow-x: hidden;
              overflow-y: auto;
            }
                        
            ::-webkit-scrollbar {
              background-color: #fff;
              width: 10px;
              height: 10px;
            }
                        
            ::-webkit-scrollbar-thumb {
              background: #bbb;
              min-height: 40px;
            }
                        
            .BefZa__NotificationsBell__markAllAsRead {
              margin: 0;
              text-transform: uppercase;
              text-decoration: none;
              line-height: 40px;
              text-align: center;
              color: #908674;
              font-size: 11px;
              font-weight: 700;
              box-sizing: border-box;
              padding: 0;
              border-radius: 0px;
              border: none;
              border-top: 1px solid #ddd;
            }
                        
            .BefZa__NotificationsBell__markAllAsRead:focus {
              outline: none;
            }
                        
            .BefZa__NotificationsBell__markAllAsRead:hover {
              border: none;
              background: #eee;
              border-top: 1px solid #ddd;
            }
                        
            ._33yR2__NotificationsBell__emptyMessage {
              padding: 0;
              min-height: 168px;
              max-height: 386px;
              overflow-x: hidden;
              overflow-y: auto;
              position: relative;
              background-color: #f3f3f3;
              border-bottom: 1px solid #ddd;
            }
                        
            ._33yR2__NotificationsBell__emptyMessage:after{
              content: 'You have no notifications.';
              color: #aaa;
              position: absolute;
              text-align: center;
              left: 0;
              right: 0;
              top: 50%;
              margin-top: -7px;
              font-weight: bold;
              font-size: 12px;
            }
                        
            ._3pEKc__NotificationsBell__dropdownContainer,
            ._1Npm___NotificationsBell__dropdownContent {
              display: inline;
            }
                        
            ._1xuKK__NotificationsBell__unreadCounter {
              position: relative;
              top: -3px;
              right: 10px;
              background-color: #b20000;
              color: #f3f3f3;
              text-align: center;
              font-size: 9px;
              line-height: 13px;
              padding: 0 3px 0 4px;
              border-radius: 2px;
              cursor: pointer;
              display: inline;
            }
            </style>

            <style type='text/css'>
            ._3WpcI__TrackerProjectHeader__publicTag {
              display: inline-block;
              margin-left: 2px;
            }
                        
            ._1H4r0__TrackerProjectHeader__projectName {
              width: calc(100% - 53px);
              display: inline-block;
              text-overflow: ellipsis;
              overflow: hidden;
              float: left;
            }
            </style>
            <style type='text/css'>
			  .elgg-field {
				display:inline;}
            </style>
";                    
    break;
}

echo $user_agent;