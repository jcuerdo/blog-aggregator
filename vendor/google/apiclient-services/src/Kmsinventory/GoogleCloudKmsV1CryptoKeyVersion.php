<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

namespace Google\Service\Kmsinventory;

class GoogleCloudKmsV1CryptoKeyVersion extends \Google\Model
{
  /**
   * @var string
   */
  public $algorithm;
  protected $attestationType = GoogleCloudKmsV1KeyOperationAttestation::class;
  protected $attestationDataType = '';
  public $attestation;
  /**
   * @var string
   */
  public $createTime;
  /**
   * @var string
   */
  public $destroyEventTime;
  /**
   * @var string
   */
  public $destroyTime;
  protected $externalProtectionLevelOptionsType = GoogleCloudKmsV1ExternalProtectionLevelOptions::class;
  protected $externalProtectionLevelOptionsDataType = '';
  public $externalProtectionLevelOptions;
  /**
   * @var string
   */
  public $generateTime;
  /**
   * @var string
   */
  public $importFailureReason;
  /**
   * @var string
   */
  public $importJob;
  /**
   * @var string
   */
  public $importTime;
  /**
   * @var string
   */
  public $name;
  /**
   * @var string
   */
  public $protectionLevel;
  /**
   * @var bool
   */
  public $reimportEligible;
  /**
   * @var string
   */
  public $state;

  /**
   * @param string
   */
  public function setAlgorithm($algorithm)
  {
    $this->algorithm = $algorithm;
  }
  /**
   * @return string
   */
  public function getAlgorithm()
  {
    return $this->algorithm;
  }
  /**
   * @param GoogleCloudKmsV1KeyOperationAttestation
   */
  public function setAttestation(GoogleCloudKmsV1KeyOperationAttestation $attestation)
  {
    $this->attestation = $attestation;
  }
  /**
   * @return GoogleCloudKmsV1KeyOperationAttestation
   */
  public function getAttestation()
  {
    return $this->attestation;
  }
  /**
   * @param string
   */
  public function setCreateTime($createTime)
  {
    $this->createTime = $createTime;
  }
  /**
   * @return string
   */
  public function getCreateTime()
  {
    return $this->createTime;
  }
  /**
   * @param string
   */
  public function setDestroyEventTime($destroyEventTime)
  {
    $this->destroyEventTime = $destroyEventTime;
  }
  /**
   * @return string
   */
  public function getDestroyEventTime()
  {
    return $this->destroyEventTime;
  }
  /**
   * @param string
   */
  public function setDestroyTime($destroyTime)
  {
    $this->destroyTime = $destroyTime;
  }
  /**
   * @return string
   */
  public function getDestroyTime()
  {
    return $this->destroyTime;
  }
  /**
   * @param GoogleCloudKmsV1ExternalProtectionLevelOptions
   */
  public function setExternalProtectionLevelOptions(GoogleCloudKmsV1ExternalProtectionLevelOptions $externalProtectionLevelOptions)
  {
    $this->externalProtectionLevelOptions = $externalProtectionLevelOptions;
  }
  /**
   * @return GoogleCloudKmsV1ExternalProtectionLevelOptions
   */
  public function getExternalProtectionLevelOptions()
  {
    return $this->externalProtectionLevelOptions;
  }
  /**
   * @param string
   */
  public function setGenerateTime($generateTime)
  {
    $this->generateTime = $generateTime;
  }
  /**
   * @return string
   */
  public function getGenerateTime()
  {
    return $this->generateTime;
  }
  /**
   * @param string
   */
  public function setImportFailureReason($importFailureReason)
  {
    $this->importFailureReason = $importFailureReason;
  }
  /**
   * @return string
   */
  public function getImportFailureReason()
  {
    return $this->importFailureReason;
  }
  /**
   * @param string
   */
  public function setImportJob($importJob)
  {
    $this->importJob = $importJob;
  }
  /**
   * @return string
   */
  public function getImportJob()
  {
    return $this->importJob;
  }
  /**
   * @param string
   */
  public function setImportTime($importTime)
  {
    $this->importTime = $importTime;
  }
  /**
   * @return string
   */
  public function getImportTime()
  {
    return $this->importTime;
  }
  /**
   * @param string
   */
  public function setName($name)
  {
    $this->name = $name;
  }
  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }
  /**
   * @param string
   */
  public function setProtectionLevel($protectionLevel)
  {
    $this->protectionLevel = $protectionLevel;
  }
  /**
   * @return string
   */
  public function getProtectionLevel()
  {
    return $this->protectionLevel;
  }
  /**
   * @param bool
   */
  public function setReimportEligible($reimportEligible)
  {
    $this->reimportEligible = $reimportEligible;
  }
  /**
   * @return bool
   */
  public function getReimportEligible()
  {
    return $this->reimportEligible;
  }
  /**
   * @param string
   */
  public function setState($state)
  {
    $this->state = $state;
  }
  /**
   * @return string
   */
  public function getState()
  {
    return $this->state;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudKmsV1CryptoKeyVersion::class, 'Google_Service_Kmsinventory_GoogleCloudKmsV1CryptoKeyVersion');
